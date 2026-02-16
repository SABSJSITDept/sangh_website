<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Feedback</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                We Value Your Feedback
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Please let us know your thoughts or suggestions.
            </p>
        </div>
        <form id="feedbackForm" class="mt-8 space-y-6" action="#" method="POST">
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input id="name" name="name" type="text" required
                        class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="John Doe">
                </div>
                <div class="mb-4">
                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                    <input id="mobile" name="mobile" type="tel" required
                        class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="+91 1234567890">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" required
                        class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="you@example.com">
                </div>
                <div class="mb-4">
                    <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Your Feedback</label>
                    <textarea id="feedback" name="feedback" rows="4" required
                        class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Tell us how we can improve..."></textarea>
                </div>
            </div>

            <div>
                <button type="submit" id="submitBtn"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <!-- Heroicon name: solid/lock-closed -->
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    Submit Feedback
                </button>
            </div>
            <div id="message" class="mt-4 text-center text-sm hidden"></div>
        </form>
    </div>

    <script>
        document.getElementById('feedbackForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            const messageDiv = document.getElementById('message');

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Submitting...';
            messageDiv.classList.add('hidden');
            messageDiv.className = 'mt-4 text-center text-sm hidden'; // reset classes

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/api/app-feedback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    messageDiv.textContent = result.message || 'Feedback submitted successfully!';
                    messageDiv.classList.remove('hidden');
                    messageDiv.classList.add('text-green-600');
                    this.reset();
                } else {
                    let errorMsg = result.message || 'Validation failed.';
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).flat().join(', ');
                    }
                    throw new Error(errorMsg);
                }
            } catch (error) {
                messageDiv.textContent = error.message;
                messageDiv.classList.remove('hidden');
                messageDiv.classList.add('text-red-600');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit Feedback';
            }
        });
    </script>
</body>

</html>