<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
</head>
<body class="bg-gray-400">
    <div id="app">
        <form @submit.prevent="submitForm" class="block mx-auto mb-8 lg:w-1/3 p-8">
            <h1 class="text-gray-800 font-medium text-2xl text-center pb-4">{{ config('app.name') }}</h1>
            <textarea
                v-model="input"
                class="w-full px-5 py-4 text-gray-700 bg-gray-200 rounded"
                placeholder="Extensions separated by new line"
                aria-label="Extensions"
                rows="15"
                :disabled="processing"
            ></textarea>
            <button
                type="submit"
                class="w-full bg-blue-500 text-white font-bold py-2 px-4 border-b-4 hover:border-b-2 hover:border-t-2 border-blue-600 hover:border-blue-500 rounded"
                :disabled="processing"
            >
                <span v-if="processing">Processing...</span>
                <span v-else>Download VS Code Extensions</span>
            </button>
            <div class="w-full text-center pt-6">
                <button
                    @click="loadExample"
                    type="button"
                    class="p-3 text-sm text-gray-600 hover:bg-gray-300 rounded-full"
                >Load Example</button>
                <a
                    href="https://peterjonsson.se"
                    class="p-3 text-sm text-gray-600 hover:bg-gray-300 rounded-full"
                >Peter Jonsson</a>
            </div>
        </form>
    </div>

    <script>
        const examplePackages = [
            'redhat.java',
            'VisualStudioExptTeam.vscodeintellicode',
            'vscjava.vscode-java-debug',
            'vscjava.vscode-java-pack',
            'vscjava.vscode-java-test',
            'vscjava.vscode-maven',
            'vscode-icons-team.vscode-icons',
            'zhuangtongfa.Material-theme'
        ]

        const app = new Vue({
            el: '#app',
            data: {
                input: '',
                processing: false
            },
            methods: {
                loadExample: function () {
                    this.input = examplePackages.join('\n')
                },
                submitForm: function () {
                    if (!this.input || this.processing) {
                        return false
                    }

                    const extensions = this.input.trim().split(/\n/)
                    this.processing = true

                    response = fetch('/download-extensions', {
                        method: 'POST',
                        body: JSON.stringify({ extensions: extensions }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        response.blob().then(blob => {
                            const url = window.URL.createObjectURL(new Blob([blob]))
                            const link = document.createElement('a')
                            link.href = url
                            link.setAttribute('download', 'vs-code-extensions.zip')
                            document.body.appendChild(link)
                            link.click()
                            link.parentNode.removeChild(link)
                        }).catch(() => { this.processing = false })
                    }).catch(() => { this.processing = false })

                    return false
                }
            }
        })
    </script>
</body>
</html>
