<x-layouts.app>
    <div class="min-h-screen flex items-center justify-center pt-[110px] pb-20 relative overflow-hidden">
        
        <!-- Background Elements -->
        <div class="absolute inset-0 pointer-events-none z-0">
            <!-- Grid Lines -->
            <div class="absolute inset-0 flex justify-center w-full px-6 md:px-[60px]">
                <div class="w-full h-full border-x border-primary/10"></div>
                <div class="absolute top-1/3 w-full h-[1px] bg-primary/10"></div>
                <div class="absolute top-2/3 w-full h-[1px] bg-primary/10"></div>
            </div>
            
            <!-- Blur Effects -->
            <div class="absolute top-[20%] left-[10%] w-[40vw] h-[40vw] bg-primary/5 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[30vw] h-[30vw] bg-primary/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="w-full max-w-lg px-6 z-10 relative">
            <div class="text-center mb-8">
                <h1 class="font-script-tagline text-primary mb-0 leading-none" style="font-size: clamp(56px, 8vw, 80px);">Đăng nhập</h1>
            </div>

            <div class="bg-[#0a0d14]/80 backdrop-blur-xl border border-primary/20 p-10 md:p-12 shadow-2xl relative">
                <!-- Corner Decorations -->
                <div class="absolute top-0 left-0 w-4 h-4 border-t border-l border-primary"></div>
                <div class="absolute top-0 right-0 w-4 h-4 border-t border-r border-primary"></div>
                <div class="absolute bottom-0 left-0 w-4 h-4 border-b border-l border-primary"></div>
                <div class="absolute bottom-0 right-0 w-4 h-4 border-b border-r border-primary"></div>

                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/50 text-red-500 px-4 py-3 rounded mb-6 text-center text-sm font-light tracking-wide animate-fade-in">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6" id="login-form">
                    @csrf
                    
                    <div id="username-container">
                        <label for="username" class="block text-[12px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required 
                            class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-4 text-base focus:outline-none focus:ring-0 transition-colors placeholder-gray-600"
                            placeholder="Nhập tên đăng nhập...">
                    </div>

                    <div id="remembered-user-container" class="hidden">
                        <label class="block text-[12px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Tài khoản</label>
                        <div class="flex items-center justify-between border-b border-primary/30 py-4 w-full">
                            <div class="flex items-center">
                                <div id="remembered-avatar-container"></div>
                                <span id="remembered-username-display" class="text-white text-base tracking-wider font-light break-all"></span>
                            </div>
                            <button type="button" id="switch-account-btn" class="text-primary hover:text-white transition-colors focus:outline-none shrink-0 ml-4" title="Đổi tài khoản">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 10H4m0 0l4 4m-4-4l4-4"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div id="account-list-container" class="hidden">
                        <label class="block text-[12px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Chọn tài khoản</label>
                        <div id="accounts-list" class="space-y-0 mb-2">
                            <!-- Accounts injected via JS -->
                        </div>
                        <button type="button" id="add-account-btn" class="flex items-center gap-4 w-full border-b border-primary/30 py-4 text-gray-400 hover:text-white transition-colors group focus:outline-none">
                            <div class="w-10 h-10 flex items-center justify-center border border-primary/30 text-primary group-hover:border-primary/80 transition-colors shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <span class="text-sm tracking-wider font-light">Thêm tài khoản khác</span>
                        </button>
                    </div>

                    <div id="password-container">
                        <label for="password" class="block text-[12px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Mật khẩu</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required 
                                class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-4 pr-10 text-base focus:outline-none focus:ring-0 transition-colors placeholder-gray-600"
                                placeholder="Nhập mật khẩu...">
                            <button type="button" id="toggle-password" class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none px-2" title="Hiển thị mật khẩu">
                                <svg id="icon-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg id="icon-eye-off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Password Checkbox -->
                    <div id="remember-container" class="flex items-center gap-3 mt-4">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 bg-transparent border border-primary/50 text-primary focus:ring-primary focus:ring-offset-0">
                        <label for="remember" class="text-[12px] font-light text-gray-400 tracking-wider cursor-pointer">Ghi nhớ mật khẩu</label>
                    </div>

                    <div id="submit-container" class="pt-8">
                        <button type="submit" class="w-full group relative inline-flex items-center justify-center bg-primary/10 border border-primary text-primary px-8 py-5 text-[14px] font-bold tracking-[0.3em] uppercase hover:bg-primary hover:text-[#040810] transition-all duration-300">
                            <span class="relative z-10 flex items-center">
                                ĐĂNG NHẬP
                            </span>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center border-t border-primary/20 pt-6">
                    <p class="text-[12px] text-gray-500 font-light">
                        Chưa có tài khoản? <a href="#" class="text-primary hover:text-white transition-colors border-b border-primary/50 pb-1">Đăng ký ngay</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const usernameContainer = document.getElementById('username-container');
            const rememberedUserContainer = document.getElementById('remembered-user-container');
            const rememberedUsernameDisplay = document.getElementById('remembered-username-display');
            const switchAccountBtn = document.getElementById('switch-account-btn');
            const loginForm = document.getElementById('login-form');
            const passwordInput = document.getElementById('password');
            const rememberCheckbox = document.getElementById('remember');

            const accountListContainer = document.getElementById('account-list-container');
            const accountsList = document.getElementById('accounts-list');
            const addAccountBtn = document.getElementById('add-account-btn');
            const passwordContainer = document.getElementById('password-container');
            const rememberContainer = document.getElementById('remember-container');
            const submitContainer = document.getElementById('submit-container');

            const togglePasswordBtn = document.getElementById('toggle-password');
            const iconEye = document.getElementById('icon-eye');
            const iconEyeOff = document.getElementById('icon-eye-off');

            let savedAccounts = JSON.parse(localStorage.getItem('saved_accounts')) || [];
            let savedPasswords = JSON.parse(localStorage.getItem('saved_passwords')) || {};
            let savedAvatars = JSON.parse(localStorage.getItem('saved_avatars')) || {};
            let lastAccount = localStorage.getItem('last_account');

            // Migrate old single account if it exists
            const oldSavedUsername = localStorage.getItem('remembered_username');
            if (oldSavedUsername && savedAccounts.length === 0) {
                savedAccounts.push(oldSavedUsername);
                lastAccount = oldSavedUsername;
                localStorage.setItem('saved_accounts', JSON.stringify(savedAccounts));
                localStorage.setItem('last_account', lastAccount);
            }

            const oldUsername = "{{ old('username') }}";
            let displayUsername = lastAccount;
            if (oldUsername && oldUsername !== lastAccount) {
                displayUsername = null; 
            }

            function getAvatarHtml(username) {
                if (savedAvatars[username]) {
                    return `<img src="${savedAvatars[username]}" class="w-10 h-10 object-cover border border-primary/50 shrink-0 mr-6" alt="Avatar">`;
                }
                return `<div class="w-10 h-10 flex items-center justify-center border border-primary/50 text-primary uppercase font-bold text-lg shrink-0 mr-6">${username.charAt(0).toUpperCase()}</div>`;
            }

            function showStandardForm() {
                accountListContainer.classList.add('hidden');
                rememberedUserContainer.classList.add('hidden');
                
                usernameContainer.classList.remove('hidden');
                passwordContainer.classList.remove('hidden');
                rememberContainer.classList.remove('hidden');
                submitContainer.classList.remove('hidden');
                
                usernameInput.value = '';
                passwordInput.value = '';
                if (rememberCheckbox) rememberCheckbox.checked = false;
                
                usernameInput.focus();
            }

            function showRememberedUser(username) {
                accountListContainer.classList.add('hidden');
                usernameContainer.classList.add('hidden');
                
                rememberedUsernameDisplay.textContent = username;
                usernameInput.value = username;
                
                const avatarContainer = document.getElementById('remembered-avatar-container');
                if (avatarContainer) {
                    avatarContainer.innerHTML = getAvatarHtml(username);
                }
                
                // Load saved password if exists
                if (savedPasswords[username]) {
                    passwordInput.value = savedPasswords[username];
                    if (rememberCheckbox) rememberCheckbox.checked = true;
                } else {
                    passwordInput.value = '';
                    if (rememberCheckbox) rememberCheckbox.checked = false;
                }
                
                rememberedUserContainer.classList.remove('hidden');
                passwordContainer.classList.remove('hidden');
                rememberContainer.classList.remove('hidden');
                submitContainer.classList.remove('hidden');
                
                setTimeout(() => { 
                    if (!passwordInput.value) {
                        passwordInput.focus(); 
                    } else {
                        loginForm.querySelector('button[type="submit"]').focus();
                    }
                }, 100);
            }

            function showAccountList() {
                usernameContainer.classList.add('hidden');
                rememberedUserContainer.classList.add('hidden');
                passwordContainer.classList.add('hidden');
                rememberContainer.classList.add('hidden');
                submitContainer.classList.add('hidden');
                
                accountsList.innerHTML = '';
                savedAccounts.forEach(account => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'flex items-center w-full border-b border-primary/30 py-4 text-gray-400 hover:text-white transition-colors focus:outline-none';
                    btn.innerHTML = `
                        ${getAvatarHtml(account)}
                        <span class="text-sm tracking-wider font-light break-all text-left">${account}</span>
                    `;
                    btn.addEventListener('click', () => {
                        showRememberedUser(account);
                        lastAccount = account;
                        localStorage.setItem('last_account', account);
                    });
                    accountsList.appendChild(btn);
                });
                
                accountListContainer.classList.remove('hidden');
            }

            if (displayUsername && savedAccounts.includes(displayUsername)) {
                showRememberedUser(displayUsername);
            } else if (savedAccounts.length > 0 && !oldUsername) {
                showRememberedUser(savedAccounts[0]);
            }

            switchAccountBtn.addEventListener('click', function() {
                showAccountList();
            });

            addAccountBtn.addEventListener('click', function() {
                showStandardForm();
            });

            togglePasswordBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    iconEye.classList.add('hidden');
                    iconEyeOff.classList.remove('hidden');
                    togglePasswordBtn.title = "Ẩn mật khẩu";
                } else {
                    passwordInput.type = 'password';
                    iconEye.classList.remove('hidden');
                    iconEyeOff.classList.add('hidden');
                    togglePasswordBtn.title = "Hiển thị mật khẩu";
                }
            });

            loginForm.addEventListener('submit', function() {
                const val = usernameInput.value.trim();
                const pass = passwordInput.value;
                if (val !== '') {
                    savedAccounts = savedAccounts.filter(acc => acc !== val);
                    savedAccounts.push(val);
                    localStorage.setItem('saved_accounts', JSON.stringify(savedAccounts));
                    localStorage.setItem('last_account', val);
                    
                    if (rememberCheckbox && rememberCheckbox.checked) {
                        savedPasswords[val] = pass;
                    } else {
                        delete savedPasswords[val];
                    }
                    localStorage.setItem('saved_passwords', JSON.stringify(savedPasswords));
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
