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

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="username" class="block text-[12px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required 
                            class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-4 text-base focus:outline-none focus:ring-0 transition-colors placeholder-gray-600"
                            placeholder="Nhập tên đăng nhập...">
                    </div>

                    <div>
                        <label for="password" class="block text-[12px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Mật khẩu</label>
                        <input type="password" id="password" name="password" required 
                            class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-4 text-base focus:outline-none focus:ring-0 transition-colors placeholder-gray-600"
                            placeholder="Nhập mật khẩu...">
                    </div>

                    <div class="pt-8">
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
</x-layouts.app>
