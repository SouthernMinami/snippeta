const languageList = document.getElementById('language-list')
const languageItems = languageList.querySelectorAll('.language-item')
const copyBtn = document.getElementById('copy-btn')
const downloadBtn = document.getElementById('download-btn')
const postBtn = document.getElementById('post-btn')
const extensions = { "Javascript": "js", "Python": "py", "PHP": "php", "Ruby": "rb", "Java": "java", "C": "c", "C#": "cs", "C++": "cpp", "Swift": "swift", "Go": "go", "Scala": "scala", "Kotlin": "kt", "Typescript": "ts", "Rust": "rs", "Shell": "sh", "SQL": "sql", "Plaintext": "txt" };

languageItems[0].setAttribute('class', 'language-item selected')

// Monaco Editor
require.config({
    paths: {
        'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs'
    }
})

require(['vs/editor/editor.main'], () => {
    const editor = monaco.editor.create(document.getElementById('editor'), {
        value: [
            'console.log("---------")'
        ].join('\n'),
        language: 'javascript',
        automaticLayout: true,
        theme: 'vs-dark'
    })

    editor.onDidChangeModelContent(() => {
    })

    // 言語のドロップダウンリストのクリックイベント
    languageItems.forEach(item => {
        item.addEventListener('click', () => {
            const prev = languageList.querySelector('.selected')
            prev.setAttribute('class', 'language-item')
            item.setAttribute('class', 'language-item selected')

            const language = item.textContent
            downloadBtn.textContent = `Download .${extensions[language]} file`
            downloadBtn.setAttribute('download', `new_snippet.${extensions[language]}`)

            // Monaco Editorのシンタックスハイライトを変更
            monaco.editor.setModelLanguage(editor.getModel(), language.toLowerCase())
        })
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

    // 投稿ボタンのクリックイベント
    postBtn.addEventListener('click', async (e) => {


        const reqJSON = {
            "title": document.getElementById('title').value,
            "language": Array.from(languageItems).find(item => item.getAttribute('class') === 'language-item selected').textContent,
            "url": "https://localhost:8000/snippet?id=",
            "content": editor.getValue(),
            "expirationDate": document.getElementById('expiration').value
        }

        await fetch('../../Database/Seeds/SnippetsSeeder.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'text/plain'
            },
            body: JSON.stringify(reqJSON)
        })

        alert('Your snippet has been posted!')
    })
})





