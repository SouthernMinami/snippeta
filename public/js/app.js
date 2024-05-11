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
        console.log(editor.getValue())
    })
})