<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartRx Portal — Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-50/50 antialiased selection:bg-blue-500 selection:text-white">

    <div class="flex min-h-full">
        
        <div class="flex flex-1 flex-col justify-center px-6 py-12 sm:px-6 lg:flex-none lg:w-[480px] lg:px-20 bg-white shadow-2xl z-20">
            <div class="mx-auto w-full max-w-sm lg:w-80">
                
                <div>
                    <div class="flex mb-6">
                        <div class="h-10 px-3 rounded-xl bg-blue-600 flex items-center justify-center font-black text-white text-lg tracking-wider shadow-lg shadow-blue-500/20">
                            Smart Rx
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Welcome Back</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Sign in to access your prescription workstation.
                    </p>
                </div>

                <div class="mt-10">
                    
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg bg-red-50 border border-red-200/60 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0 text-red-500">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Invalid authentication details</h3>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                Email Address
                            </label>
                            <div class="mt-1.5 relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                    class="pl-10 block w-full text-sm border-slate-200 bg-slate-50/50 rounded-lg p-3 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none border transition-all duration-150" 
                                    placeholder="doctor@smartrx.com" />
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Password
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                        Forgot?
                                    </a>
                                @endif
                            </div>
                            <div class="mt-1.5 relative rounded-lg">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </div>
                                
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="pl-10 pr-10 block w-full text-sm border-slate-200 bg-slate-50/50 rounded-lg p-3 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:outline-none border transition-all duration-150" 
                                    placeholder="••••••••" />
                                
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-600 transition-colors focus:outline-none">
                                    <svg id="eyeIcon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eyeOffIcon" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500/10 border-slate-300 rounded cursor-pointer transition-all">
                            <label for="remember_me" class="ml-2 block text-sm text-slate-600 select-none cursor-pointer">
                                Keep me logged in
                            </label>
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 shadow-blue-500/10 transition-all duration-150 active:scale-[0.985]">
                                Sign In to Portal
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>

        <div class="relative hidden w-0 flex-1 lg:block bg-slate-900 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-slate-900 to-slate-950 z-10"></div>
            
            <div class="absolute inset-0 opacity-10 z-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>

            <div class="absolute inset-0 flex flex-col justify-between p-12 z-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center font-black text-white text-lg tracking-wider shadow-lg shadow-blue-500/30">
                        Rx
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">SmartRx <span class="text-blue-400 font-normal">Core</span></span>
                </div>

                <div class="max-w-md">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">
                        v1.0 Production Ready
                    </span>
                    <h1 class="text-4xl font-bold tracking-tight text-white leading-tight">
                        Intelligent workflows for modern healthcare.
                    </h1>
                    <p class="mt-4 text-base text-slate-400 leading-relaxed">
                        Access real-time pharmacy inventories, instant patient timelines, and secure cloud records engineered from the ground up.
                    </p>
                </div>

                <p class="text-xs text-slate-500">
                    &copy; 2026 SmartRx Software Architecture. All rights reserved.
                </p>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            toggleButton.addEventListener('click', function () {
                // টাইপ সোয়াপিং লজিক
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                }
            });
        });
    </script>

</body>
</html>