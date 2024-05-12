const languageList = document.getElementById('language-list')
const languageItems = languageList.querySelectorAll('.language-item')
const downloadBtn = document.getElementById('download-btn')
const extensions = { "Javascript": "js", "Python": "py", "PHP": "php", "Ruby": "rb", "Java": "java", "C": "c", "C#": "cs", "C++": "cpp", "Swift": "swift", "Go": "go", "Scala": "scala", "Kotlin": "kt", "Typescript": "ts", "Rust": "rs", "Shell": "sh", "SQL": "sql", "Plaintext": "txt" };

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
            const language = item.textContent
            downloadBtn.textContent = `Download .${extensions[language]} file`
            downloadBtn.setAttribute('download', `new_snippet.${extensions[language]}`)

            // Monaco Editorのシンタックスハイライトを変更
            monaco.editor.setModelLanguage(editor.getModel(), language.toLowerCase())
        })
    })
})



