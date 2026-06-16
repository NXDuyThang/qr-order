<x-layouts.app>
    @push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
    <style>
        .cropper-view-box, .cropper-face {
            border-radius: 50%;
        }
    </style>
    @endpush
    <div class="min-h-screen pt-[150px] pb-24 relative overflow-hidden">
        
        <!-- Background Elements -->
        <div class="absolute inset-0 pointer-events-none z-0">
            <div class="absolute inset-0 flex justify-center w-full px-6 md:px-[60px]">
                <div class="w-full h-full border-x border-primary/10"></div>
            </div>
            <div class="absolute top-0 right-0 w-[50vw] h-[50vw] bg-primary/5 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-6xl mx-auto px-6 z-10 relative">
            <div class="mb-12">
                <h1 class="font-script-tagline text-[40px] md:text-[56px] text-primary mb-2 leading-none">Hồ sơ của bạn</h1>
                <div class="flex items-center gap-4">
                    <p class="text-[12px] tracking-[0.3em] text-gray-400 uppercase font-light">
                        Quản lý tài khoản
                    </p>
                    <div class="h-[1px] w-12 bg-primary/40"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Sidebar Navigation -->
                <div class="md:col-span-1">
                    <div class="bg-[#0a0d14]/80 backdrop-blur-xl border border-primary/20 p-8 sticky top-[130px]">
                        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-primary/20">
                            <div class="relative group cursor-pointer inline-block" onclick="document.getElementById('quickAvatarInput').click()">
                                @if(isset($userInfo['avatar']) && $userInfo['avatar'])
                                    <img src="{{ $userInfo['avatar'] }}" alt="Avatar" class="w-16 h-16 rounded-full border-2 border-primary object-cover group-hover:opacity-80 transition-opacity">
                                @else
                                    <div class="w-16 h-16 rounded-full border-2 border-primary bg-primary/10 flex items-center justify-center text-primary text-2xl font-script-tagline group-hover:opacity-80 transition-opacity">
                                        {{ substr($userInfo['firstname'] ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute bottom-0 right-0 w-6 h-6 bg-[#0a0d14] rounded-full border border-primary/50 flex items-center justify-center text-primary opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <form id="quickAvatarForm" method="POST" action="{{ route('profile.update_avatar') }}" class="hidden">
                                @csrf
                                <input type="hidden" name="avatar" id="quickAvatarBase64">
                                <input type="file" id="quickAvatarInput" accept="image/*" onchange="handleQuickAvatar(this)">
                            </form>
                            <div>
                                <h3 class="text-white font-medium">{{ $userInfo['firstname'] ?? '' }} {{ $userInfo['lastname'] ?? '' }}</h3>
                                <p class="text-[11px] text-gray-400 mt-1">@ {{ $userInfo['username'] ?? 'user' }}</p>
                            </div>
                        </div>

                        <nav class="flex flex-col gap-3" id="profile-tabs">
                            <button onclick="switchTab('info')" class="tab-btn w-full text-left px-4 py-4 text-[12px] tracking-[0.1em] uppercase transition-all duration-300 border-l-2 border-primary text-primary bg-primary/10" data-tab="info">Thông tin chung</button>
                            <button onclick="switchTab('password')" class="tab-btn w-full text-left px-4 py-4 text-[12px] tracking-[0.1em] uppercase transition-all duration-300 border-l-2 border-transparent text-gray-400 hover:text-white hover:border-primary/50" data-tab="password">Đổi mật khẩu</button>
                            <button onclick="switchTab('cccd')" class="tab-btn w-full text-left px-4 py-4 text-[12px] tracking-[0.1em] uppercase transition-all duration-300 border-l-2 border-transparent text-gray-400 hover:text-white hover:border-primary/50" data-tab="cccd">Căn cước công dân</button>
                            
                            <form method="POST" action="{{ route('logout') }}" class="mt-8 pt-4 border-t border-primary/20">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-4 text-[12px] tracking-[0.1em] uppercase text-red-400 hover:text-red-300 transition-colors">
                                    Đăng xuất
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>

                <!-- Tab Contents -->
                <div class="md:col-span-3 bg-[#0a0d14]/80 backdrop-blur-xl border border-primary/20 p-8 md:p-12 relative min-h-[500px]">
                    <!-- Corner Decorations -->
                    <div class="absolute top-0 left-0 w-3 h-3 border-t border-l border-primary"></div>
                    <div class="absolute top-0 right-0 w-3 h-3 border-t border-r border-primary"></div>
                    <div class="absolute bottom-0 left-0 w-3 h-3 border-b border-l border-primary"></div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 border-b border-r border-primary"></div>

                    <style>
                        select option {
                            color: #000000 !important;
                            background-color: #ffffff !important;
                        }
                    </style>

                    <!-- TAB: Info -->
                    <div id="tab-info" class="tab-content block">
                        <h2 class="text-2xl font-light text-white mb-8 tracking-wide">Thông tin chung</h2>
                        <form method="POST" action="{{ route('profile.update_info') }}" class="space-y-8">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Tên</label>
                                    <input type="text" name="firstname" value="{{ $userInfo['firstname'] ?? '' }}" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Họ</label>
                                    <input type="text" name="lastname" value="{{ $userInfo['lastname'] ?? '' }}" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Giới thiệu bản thân</label>
                                    <textarea name="intro" rows="3" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">{{ $userInfo['intro'] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Số điện thoại</label>
                                    <input type="text" name="phone" value="{{ $userInfo['phone'] ?? '' }}" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Giới tính</label>
                                    <select name="gender" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                                        <option value="1" class="text-black bg-white" {{ ($userInfo['gender'] ?? 1) == 1 ? 'selected' : '' }}>Nam</option>
                                        <option value="0" class="text-black bg-white" {{ ($userInfo['gender'] ?? 1) == 0 ? 'selected' : '' }}>Nữ</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Ngày sinh</label>
                                    <input type="date" name="dob" value="{{ $userInfo['dob'] ?? '' }}" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors [color-scheme:dark]">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Nơi sinh</label>
                                    <input type="text" name="pob" value="{{ $userInfo['pob'] ?? '' }}" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                                </div>
                                
                                <div class="md:col-span-2 mt-4">
                                    <h4 class="text-[13px] font-medium text-primary mb-4 tracking-wider">Thông tin địa chỉ</h4>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Tỉnh / Thành phố</label>
                                    <select id="province_select" name="province" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors" onchange="loadAdministratives(this.value)">
                                        <option value="" class="text-black bg-white">Chọn tỉnh thành</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov['id'] }}" class="text-black bg-white" {{ ($userInfo['province'] ?? '') == $prov['id'] ? 'selected' : '' }}>
                                                {{ $prov['title'] ?? $prov['name'] ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Quận / Huyện</label>
                                    <select id="administrative_select" name="administrative" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                                        <option value="" class="text-black bg-white">Chọn quận huyện</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pt-8">
                                <button type="submit" class="bg-primary/10 border border-primary text-primary px-8 py-3 text-[12px] font-bold tracking-[0.3em] uppercase hover:bg-primary hover:text-[#040810] transition-all duration-300">
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB: Password -->
                    <div id="tab-password" class="tab-content hidden">
                        <h2 class="text-2xl font-light text-white mb-8 tracking-wide">Đổi mật khẩu</h2>
                        <form method="POST" action="{{ route('profile.update_password') }}" class="space-y-8 max-w-xl">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Mật khẩu hiện tại</label>
                                <input type="password" name="old_password" required class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Mật khẩu mới</label>
                                <input type="password" name="password" required class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" required class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                            </div>
                            <div class="pt-6">
                                <button type="submit" class="bg-primary/10 border border-primary text-primary px-8 py-3 text-[12px] font-bold tracking-[0.3em] uppercase hover:bg-primary hover:text-[#040810] transition-all duration-300">
                                    Cập nhật mật khẩu
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB: CCCD -->
                    <div id="tab-cccd" class="tab-content hidden">
                        <h2 class="text-2xl font-light text-white mb-8 tracking-wide">Thông tin CCCD</h2>
                        <form method="POST" action="{{ route('profile.update_cccd') }}" class="space-y-8">
                            @csrf
                            <input type="hidden" name="front" id="cccdFrontBase64">
                            <input type="hidden" name="back" id="cccdBackBase64">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                                <!-- Mặt trước -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Ảnh CCCD Mặt Trước</label>
                                    <div class="h-40 border-2 border-dashed border-primary/30 rounded-lg flex flex-col items-center justify-center relative bg-primary/5 hover:bg-primary/10 transition-colors">
                                        <img id="cccdFrontPreview" class="absolute inset-0 w-full h-full object-contain hidden p-2">
                                        <div id="cccdFrontPlaceholder" class="text-center pointer-events-none">
                                            <svg class="w-10 h-10 text-primary/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="text-[12px] text-gray-400 tracking-wider">Tải lên mặt trước</span>
                                        </div>
                                        <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" onchange="previewAndConvertToBase64(this, 'cccdFrontPreview', 'cccdFrontPlaceholder', 'cccdFrontBase64')">
                                    </div>
                                </div>
                                <!-- Mặt sau -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Ảnh CCCD Mặt Sau</label>
                                    <div class="h-40 border-2 border-dashed border-primary/30 rounded-lg flex flex-col items-center justify-center relative bg-primary/5 hover:bg-primary/10 transition-colors">
                                        <img id="cccdBackPreview" class="absolute inset-0 w-full h-full object-contain hidden p-2">
                                        <div id="cccdBackPlaceholder" class="text-center pointer-events-none">
                                            <svg class="w-10 h-10 text-primary/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span class="text-[12px] text-gray-400 tracking-wider">Tải lên mặt sau</span>
                                        </div>
                                        <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" onchange="previewAndConvertToBase64(this, 'cccdBackPreview', 'cccdBackPlaceholder', 'cccdBackBase64')">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Số CCCD</label>
                                    <input type="text" name="number" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Ngày cấp</label>
                                    <input type="date" name="date" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors [color-scheme:dark]">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-semibold text-gray-400 tracking-[0.2em] uppercase mb-3">Nơi cấp</label>
                                    <input type="text" name="place" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-sm focus:outline-none transition-colors">
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="bg-primary/10 border border-primary text-primary px-8 py-3 text-[12px] font-bold tracking-[0.3em] uppercase hover:bg-primary hover:text-[#040810] transition-all duration-300">
                                    Lưu thông tin CCCD
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div id="cropperModal" class="fixed inset-0 bg-[#0a0d14]/90 hidden flex items-center justify-center backdrop-blur-sm" style="z-index: 9999;">
        <div class="bg-[#0a0d14] border border-primary/30 p-6 max-w-md w-full m-4 shadow-2xl rounded-xl">
            <h3 class="text-white text-lg font-light tracking-wide mb-6">Chỉnh sửa ảnh đại diện</h3>
            <div class="w-full h-64 bg-black mb-4 flex items-center justify-center rounded-lg overflow-hidden">
                <img id="cropperImage" class="max-w-full max-h-full">
            </div>
            
            <div class="flex items-center gap-3 mb-6 px-2 relative z-[60] pointer-events-auto">
                <button type="button" onclick="window.zoomCropper(-0.1)" class="text-gray-400 hover:text-white transition-colors focus:outline-none p-1 cursor-pointer">
                    <svg style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
                </button>
                <input type="range" id="zoomSlider" class="w-full cursor-pointer accent-primary relative z-[60]" min="0" max="1" step="0.01" value="0">
                <button type="button" onclick="window.zoomCropper(0.1)" class="text-gray-400 hover:text-white transition-colors focus:outline-none p-1 cursor-pointer">
                    <svg style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                </button>
            </div>

            <div class="flex justify-end gap-4 relative z-[60]">
                <button type="button" onclick="closeCropper()" class="text-gray-400 hover:text-white px-4 py-2 text-[12px] tracking-wider uppercase transition-colors">Hủy</button>
                <button type="button" onclick="saveCroppedAvatar()" class="bg-primary/10 border border-primary text-primary px-6 py-2 text-[12px] font-bold tracking-[0.2em] uppercase hover:bg-primary hover:text-[#0a0d14] transition-all duration-300">Lưu Ảnh</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        // Tab Switching Logic
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary', 'bg-primary/10');
                btn.classList.add('border-transparent', 'text-gray-400');
            });
            
            const activeBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
            if (activeBtn) {
                activeBtn.classList.remove('border-transparent', 'text-gray-400');
                activeBtn.classList.add('border-primary', 'text-primary', 'bg-primary/10');
            }
        }

        // Image to Base64 preview and input filling
        function previewAndConvertToBase64(input, previewId, placeholderId, base64InputId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    document.getElementById(placeholderId).classList.add('hidden');
                    document.getElementById(base64InputId).value = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // AJAX Load Administratives
        function loadAdministratives(provinceId) {
            const adminSelect = document.getElementById('administrative_select');
            adminSelect.innerHTML = '<option value="">Đang tải...</option>';
            
            if (!provinceId) {
                adminSelect.innerHTML = '<option value="">Chọn quận huyện</option>';
                return;
            }

            fetch('{{ route("api.administratives") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ province_id: provinceId })
            })
            .then(res => res.json())
            .then(data => {
                adminSelect.innerHTML = '<option value="">Chọn quận huyện</option>';
                if (data && data.data) {
                    data.data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.title || item.name;
                        option.className = 'text-black bg-white';
                        adminSelect.appendChild(option);
                    });
                }
            })
            .catch(err => {
                console.error('Lỗi khi tải quận huyện', err);
                adminSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
            });
        }

        // Pre-load administratives if province is already selected
        document.addEventListener('DOMContentLoaded', () => {
            const provSelect = document.getElementById('province_select');
            if (provSelect.value) {
                loadAdministratives(provSelect.value);
            }
        });
        let cropper = null;
        let cropperEventsAttached = false;

        window.zoomCropper = function(ratio) {
            if (cropper) {
                cropper.zoom(ratio);
            }
        };

        function handleQuickAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const image = document.getElementById('cropperImage');
                    image.src = e.target.result;
                    document.getElementById('cropperModal').classList.remove('hidden');
                    
                    if (cropper) {
                        cropper.destroy();
                    }
                    
                    if (!cropperEventsAttached) {
                        const slider = document.getElementById('zoomSlider');
                        slider.addEventListener('input', function(e) {
                            if (cropper) {
                                cropper.zoomTo(Number(e.target.value));
                            }
                        });
                        
                        image.addEventListener('zoom', function(e) {
                            // Prevent slider from snapping while user is actively dragging it
                            if (document.activeElement !== slider) {
                                slider.value = e.detail.ratio;
                            }
                        });
                        cropperEventsAttached = true;
                    }
                    
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 0.8,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: true,
                        zoomOnWheel: true,
                        wheelZoomRatio: 0.1,
                        ready: function () {
                            try {
                                const slider = document.getElementById('zoomSlider');
                                const imageData = cropper.getImageData();
                                const canvasData = cropper.getCanvasData();
                                const cropBoxData = cropper.getCropBoxData();
                                
                                const nW = imageData.naturalWidth || 1;
                                const nH = imageData.naturalHeight || 1;
                                const minZoom = Math.max(cropBoxData.width / nW, cropBoxData.height / nH) || 0.1;
                                
                                slider.min = minZoom;
                                slider.max = minZoom * 4;
                                slider.step = 0.01;
                                slider.value = canvasData.width / nW || minZoom;
                            } catch (e) {
                                console.error("Slider setup error:", e);
                            }
                        }
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
            input.value = '';
        }

        function closeCropper() {
            document.getElementById('cropperModal').classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }

        function saveCroppedAvatar() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                });
                
                document.getElementById('quickAvatarBase64').value = canvas.toDataURL('image/jpeg', 0.9);
                document.getElementById('quickAvatarForm').submit();
            }
        }
    </script>
    @endpush
</x-layouts.app>
