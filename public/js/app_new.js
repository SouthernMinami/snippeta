document.addEventListener('DOMContentLoaded', () => {
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

        const languageList = document.getElementById('language-list')
        const languageItems = languageList.querySelectorAll('.language-item')
        const copyBtn = document.getElementById('copy-btn')
        const downloadBtn = document.getElementById('download-btn')
        const postBtn = document.getElementById('post-btn')
        const extensions = { "JavaScript": "js", "Python": "py", "PHP": "php", "Ruby": "rb", "Java": "java", "C": "c", "C#": "cs", "C++": "cpp", "Swift": "swift", "Go": "go", "Scala": "scala", "Kotlin": "kt", "TypeScript": "ts", "Rust": "rs", "Shell": "sh", "SQL": "sql", "Plaintext": "txt" };

        languageItems[0].setAttribute('class', 'language-item selected')

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
                console.log("Language changed to " + language)
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
        postBtn.addEventListener('click', () => {
            const postData = {
                "title": document.getElementById('title').value,
                "language": Array.from(languageItems).find(item => item.getAttribute('class') === 'language-item selected').textContent,
                "path": "",
                "content": editor.getValue(),
                "expirationDate": document.getElementById('expiration').value
            }

            fetch('/Helpers/execSeedCmd.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(postData)
            })
                .then(res => res.text())
                .then(data => {
                    console.log(data)
                    if (data === 'Not string') {
                        alert('Error: The provided value is not a string.')
                        return
                    }
                    if (data === 'Empty string') {
                        alert('Error: Please enter all the required fields.')
                        return
                    }
                    if (data === 'Too long code') {
                        alert('Error: The length of the provided value exceeds the maximum length.')
                        return
                    }
                    if (data === 'Empty code') {
                        alert('Error: The code input is empty.')
                        return
                    }
                    if (data === 'Not UTF-8 code') {
                        alert('Error: The code input is not UTF-8.')
                        return
                    }
                    alert('Posted a new snippet!')
                })
                .catch(err => {
                    console.error(err)
                    alert('Failed to post.')
                })
        })
    })
})