const languageList = document.getElementById('language-list')
const copyBtn = document.getElementById('copy-btn')
const downloadBtn = document.getElementById('download-btn')
const extensions = { "Javascript": "js", "Python": "py", "PHP": "php", "Ruby": "rb", "Java": "java", "C": "c", "C#": "cs", "C++": "cpp", "Swift": "swift", "Go": "go", "Scala": "scala", "Kotlin": "kt", "Typescript": "ts", "Rust": "rs", "Shell": "sh", "SQL": "sql", "Plaintext": "txt" };

languageList.classList.add('d-none')

// ダウンロードボタンの拡張子をスニペット投稿時の言語に変更
downloadBtn.textContent = `Download .${extensions[snippet['language']]} file`

// Monaco Editor
require.config({
    paths: {
        'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs'
    }
})

require(['vs/editor/editor.main'], () => {
    const editor = monaco.editor.create(document.getElementById('editor'), {
        value: [
            snippet['content']
        ].join('\n'),
        language: snippet['language'].toLowerCase(),
        automaticLayout: true,
        theme: 'vs-dark'
    })

    editor.onDidChangeModelContent(() => {
    })

    // コピーボタンのクリックイベント
    copyBtn.addEventListener('click', () => {
        const code = editor.getValue()
        // ナビゲータークリップボードAPIを使用してコピー
        navigator.clipboard.writeText(code).then(() => {
            copyBtn.textContent = 'Copied the code!'
            setTimeout(() => {
                copyBtn.textContent = 'Copy'
            }, 500)
        })
    })

    // ダウンロードボタンのクリックイベント
    downloadBtn.addEventListener('click', () => {
        // 選択されている言語を取得
        // querySelectorAllはNodeListを返すので、findメソッドをサポートしている配列に変換
        const language = Array.from(languageItems).find(item => item.getAttribute('class') === 'language-item selected').textContent
        const extension = extensions[language]
        const code = editor.getValue()

        const blob = new Blob([code], { type: 'text/plain' })
        const url = URL.createObjectURL(blob)

        const a = document.createElement('a')
        a.href = url
        a.download = `new_snippet.${extension}`
        a.click()
        // メモリリークを防ぐためにURLを解放
        URL.revokeObjectURL(url)
    })
})





