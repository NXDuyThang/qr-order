<x-layouts.app>
    @push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/light.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .cropper-view-box, .cropper-face {
            border-radius: 50%;
        }
        select option {
            color: #000000 !important;
            background-color: #ffffff !important;
        }
        /* Tom Select Dark Theme Overrides */
        .ts-control {
            background-color: transparent !important;
            border: none !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 0 !important;
            padding: 8px 0 !important;
            box-shadow: none !important;
        }
        .ts-control.focus {
            border-bottom: 1px solid rgba(255, 255, 255, 0.8) !important;
        }
        .ts-wrapper.single .ts-control {
            color: white;
        }
        .ts-control > input {
            color: white !important;
        }
        .ts-dropdown {
            background-color: #0a0d14 !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }
        .ts-dropdown .option {
            padding: 10px !important;
            color: white !important;
        }
        .ts-dropdown .option.active, .ts-dropdown .option:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
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

        <div class="max-w-7xl mx-auto px-6 z-10 relative" style="max-width: 1200px;">
            <div class="mb-12 flex items-end justify-between">
                <div>
                    <h1 class="font-script-tagline text-[40px] md:text-[56px] text-primary mb-2 leading-none">Hồ sơ của bạn</h1>
                    <div class="flex items-center gap-4">
                        <p class="text-[12px] tracking-[0.3em] text-gray-400 uppercase font-light">
                            Quản lý tài khoản
                        </p>
                        <div class="h-[1px] w-12 bg-primary/40"></div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button type="submit" class="text-[12px] tracking-[0.1em] uppercase text-red-400 hover:text-red-300 transition-colors inline-flex items-center gap-2 px-4 py-2 border border-red-400/30 hover:border-red-400 rounded hover:bg-red-400/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Đăng xuất
                    </button>
                </form>
            </div>

            <!-- Block 1: User Info -->
            <div class="bg-[#0a0d14]/80 backdrop-blur-xl border border-primary/20 p-8 md:p-12 relative mb-12">
                <!-- Corner Decorations -->
                <div class="absolute top-0 left-0 w-3 h-3 border-t border-l border-primary"></div>
                <div class="absolute top-0 right-0 w-3 h-3 border-t border-r border-primary"></div>
                <div class="absolute bottom-0 left-0 w-3 h-3 border-b border-l border-primary"></div>
                <div class="absolute bottom-0 right-0 w-3 h-3 border-b border-r border-primary"></div>
                
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <!-- Avatar section -->
                    <div class="flex-shrink-0 flex flex-col items-center gap-4" style="width: 150px;">
                        <div class="relative group cursor-pointer inline-block" onclick="document.getElementById('quickAvatarInput').click()">
                            @if(isset($userInfo['avatar']) && $userInfo['avatar'])
                                <img src="{{ $userInfo['avatar'] }}" alt="Avatar" class="rounded-full border-2 border-primary object-cover group-hover:opacity-80 transition-opacity shadow-[0_0_20px_rgba(var(--color-primary),0.2)]" style="width: 120px; height: 120px;">
                            @else
                                <div class="rounded-full border-2 border-primary bg-primary/10 flex items-center justify-center text-primary font-script-tagline group-hover:opacity-80 transition-opacity shadow-[0_0_20px_rgba(var(--color-primary),0.2)]" style="width: 120px; height: 120px; font-size: 40px;">
                                    {{ substr($userInfo['firstname'] ?? 'U', 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute bottom-0 right-2 w-8 h-8 bg-[#0a0d14] rounded-full border border-primary/50 flex items-center justify-center text-primary opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <form id="quickAvatarForm" method="POST" action="{{ route('profile.update_avatar') }}" class="hidden">
                            @csrf
                            <input type="hidden" name="avatar" id="quickAvatarBase64">
                            <input type="file" id="quickAvatarInput" accept="image/*" onchange="handleQuickAvatar(this)">
                        </form>
                        
                        <!-- Password Update Icon -->
                        <button type="button" onclick="openModal('passwordModal')" class="flex items-center gap-2 text-primary hover:text-white transition-colors text-[11px] tracking-widest uppercase mt-4 border border-primary/30 px-4 py-2 rounded-full hover:bg-primary/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            Đổi mật khẩu
                        </button>
                    </div>

                    <!-- Info grid -->
                    <div class="flex-grow w-full grid grid-cols-1 md:grid-cols-2" style="gap: 2rem;">
                        <h2 class="col-span-full text-4xl font-light text-white border-b border-primary/20 pb-4 mb-6 tracking-wide flex items-center gap-3">
                            <svg class="w-6 h-6 text-primary" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Thông tin người dùng
                        </h2>
                        
                        <!-- Mã code -->
                        <div class="mb-6">
                            <span class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-1">Mã Code</span>
                            <span class="text-white text-lg font-light">{{ $userInfo['code'] ?? 'Chưa cập nhật' }}</span>
                        </div>
                        
                        <!-- Họ và tên -->
                        <div class="mb-6">
                            <span class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-1">Họ và tên</span>
                            <span class="text-white text-lg font-light">
                                {{ $userInfo['name'] ?? trim(($userInfo['firstname'] ?? '') . ' ' . ($userInfo['lastname'] ?? '')) ?: 'Chưa cập nhật' }}
                            </span>
                        </div>

                        <!-- Giới tính -->
                        <div class="mb-6">
                            <span class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-1">Giới tính</span>
                            <span class="text-white text-lg font-light">
                                @if(isset($userInfo['gender']))
                                    {{ $userInfo['gender'] == 0 ? 'Nam' : ($userInfo['gender'] == 1 ? 'Nữ' : 'Khác') }}
                                @else
                                    Chưa cập nhật
                                @endif
                            </span>
                        </div>

                        <!-- Ngày sinh -->
                        <div class="mb-6">
                            <span class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-1">Ngày sinh</span>
                            <span class="text-white text-lg font-light">
                                {{ $userInfo['formatedDob'] ?? (isset($userInfo['dob']) ? \Carbon\Carbon::parse($userInfo['dob'])->format('d/m/Y') : 'Chưa cập nhật') }}
                            </span>
                        </div>

                        <!-- Nơi sinh -->
                        <div class="mb-6">
                            <span class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-1">Nơi sinh</span>
                            <span class="text-white text-lg font-light">{{ $userInfo['pob'] ?? 'Chưa cập nhật' }}</span>
                        </div>

                        <!-- CCCD -->
                        <div class="flex items-center justify-between col-span-1 border-b border-primary/10 pb-2 md:border-none md:pb-0 mb-6">
                            <div>
                                <span class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-1">Căn cước công dân</span>
                                <span class="text-white text-lg font-light">{{ $userInfo['id_number'] ?? 'Chưa cập nhật' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="openModal('viewCccdModal')" title="Xem CCCD" class="text-primary hover:text-white transition-colors p-2 border border-primary/20 rounded hover:bg-primary/10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <button type="button" onclick="openModal('cccdModal')" title="Cập nhật CCCD" class="text-primary hover:text-white transition-colors p-2 border border-primary/20 rounded hover:bg-primary/10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="col-span-full mb-6">
                            <span class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-1">Địa chỉ</span>
                            <span class="text-white text-lg font-light">
                                @php
                                    $provName = $userInfo['add_province'] ?? $userInfo['province'] ?? null;
                                    $distName = $userInfo['add_district'] ?? $userInfo['administrative'] ?? null;

                                    if ($provName && isset($provinces)) {
                                        foreach ($provinces as $p) {
                                            if ($p['id'] == $provName) {
                                                $provName = $p['title'] ?? $p['name'] ?? $provName;
                                                break;
                                            }
                                        }
                                    }

                                    if ($distName && isset($userAdministratives)) {
                                        foreach ($userAdministratives as $d) {
                                            if ($d['id'] == $distName) {
                                                $distName = $d['title'] ?? $d['name'] ?? $distName;
                                                break;
                                            }
                                        }
                                    }

                                    $addrParts = array_filter([
                                        $userInfo['add_street'] ?? null,
                                        $userInfo['add_ward'] ?? null,
                                        $distName,
                                        $provName
                                    ]);
                                    $fullAddress = implode(', ', $addrParts);
                                @endphp
                                {{ !empty($fullAddress) ? $fullAddress : 'Chưa cập nhật' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Block 2: User Update Info -->
            <div class="bg-[#0a0d14]/80 backdrop-blur-xl border border-primary/20 p-8 md:p-12 relative">
                <!-- Corner Decorations -->
                <div class="absolute top-0 left-0 w-3 h-3 border-t border-l border-primary"></div>
                <div class="absolute top-0 right-0 w-3 h-3 border-t border-r border-primary"></div>
                <div class="absolute bottom-0 left-0 w-3 h-3 border-b border-l border-primary"></div>
                <div class="absolute bottom-0 right-0 w-3 h-3 border-b border-r border-primary"></div>
                
                <h2 class="text-4xl font-light text-white mb-8 tracking-wide border-b border-primary/20 pb-4 flex items-center gap-3">
                    <svg class="w-6 h-6 text-primary" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Cập nhật thông tin
                </h2>
                
                <form method="POST" action="{{ route('profile.update_info') }}" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Tên</label>
                            <input type="text" name="firstname" value="{{ $userInfo['firstname'] ?? '' }}" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Họ</label>
                            <input type="text" name="lastname" value="{{ $userInfo['lastname'] ?? '' }}" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Giới thiệu bản thân</label>
                            <textarea name="intro" rows="3" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">{{ $userInfo['intro'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Số điện thoại</label>
                            <input type="text" name="phone" value="{{ $userInfo['phone'] ?? '' }}" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Giới tính</label>
                            <select name="gender" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                                <option value="0" class="text-black bg-white" {{ ($userInfo['gender'] ?? 0) == 0 ? 'selected' : '' }}>Nam</option>
                                <option value="1" class="text-black bg-white" {{ ($userInfo['gender'] ?? 0) == 1 ? 'selected' : '' }}>Nữ</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Ngày sinh</label>
                            <input type="text" name="dob" value="{{ $userInfo['dob'] ?? '' }}" class="custom-datepicker w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors cursor-pointer" placeholder="Chọn ngày sinh">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Nơi sinh</label>
                            <input type="text" name="pob" value="{{ $userInfo['pob'] ?? '' }}" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                        </div>
                        
                        <div class="md:col-span-2 mt-4">
                            <h4 class="text-[13px] font-medium text-primary mb-4 tracking-wider">Thông tin địa chỉ</h4>
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Tỉnh / Thành phố</label>
                            <select id="province_select" name="province" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                                <option value="" class="text-black bg-white">Chọn tỉnh thành</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov['id'] }}" class="text-black bg-white" {{ ($userInfo['province'] ?? '') == $prov['id'] ? 'selected' : '' }}>
                                        {{ $prov['title'] ?? $prov['name'] ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Quận / Huyện</label>
                            <select id="administrative_select" name="administrative" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                                <option value="" class="text-black bg-white">Chọn quận huyện</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-8 flex justify-end">
                        <button type="submit" class="bg-primary/10 border border-primary text-primary px-8 py-3 text-[12px] font-bold tracking-[0.3em] uppercase hover:bg-primary hover:text-[#040810] transition-all duration-300">
                            Cập nhật thông tin
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Block 3: Món ăn yêu thích -->
            <div id="wishlist" class="bg-[#0a0d14]/80 backdrop-blur-xl border border-primary/20 p-8 md:p-12 relative mt-12">
                <!-- Corner Decorations -->
                <div class="absolute top-0 left-0 w-3 h-3 border-t border-l border-primary"></div>
                <div class="absolute top-0 right-0 w-3 h-3 border-t border-r border-primary"></div>
                <div class="absolute bottom-0 left-0 w-3 h-3 border-b border-l border-primary"></div>
                <div class="absolute bottom-0 right-0 w-3 h-3 border-b border-r border-primary"></div>
                
                <h2 class="text-4xl font-light text-white mb-8 tracking-wide border-b border-primary/20 pb-4 flex items-center gap-3">
                    <svg class="w-6 h-6 text-primary" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Món ăn yêu thích
                </h2>

                @if($foods->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg class="w-16 h-16 text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <h2 class="text-xl text-gray-300 font-light tracking-widest mb-4">Bạn chưa lưu món ăn nào</h2>
                        <a href="{{ route('vietnamese_cuisine') }}" class="px-8 py-3 border border-primary text-primary hover:bg-primary hover:text-white transition-colors uppercase tracking-[0.2em] text-sm">
                            Khám phá thực đơn
                        </a>
                    </div>
                @else
                    <div id="portfolio-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($foods as $food)
                            <div class="portfolio-item relative aspect-[3/4] overflow-hidden group cursor-pointer" data-category="{{ $food->category->slug ?? '' }}">
                                @php
                                    $imageUrl = '/images/default-food.jpg';
                                    if (!empty($food->image)) {
                                        if (str_starts_with($food->image, '/') || str_starts_with($food->image, 'http')) {
                                            $imageUrl = $food->image;
                                        } else {
                                            $imageUrl = Storage::url($food->image);
                                        }
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $food->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                
                                <div class="absolute inset-4 bg-[#0a0f18]/95 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-center items-center text-center p-6 border border-primary/20">
                                    <h3 class="text-[15px] tracking-[0.2em] uppercase text-primary font-medium mb-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                        {{ $food->name }}
                                    </h3>
                                    <span class="text-[13px] text-gray-400 font-light tracking-widest transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-75">
                                        {{ $food->category->name ?? 'Món ngon' }}
                                    </span>
                                    
                                    <a href="{{ route('vietnamese_cuisine_detail', ['slug' => $food->slug]) }}" class="absolute inset-0 z-10">
                                        <span class="sr-only">Xem chi tiết {{ $food->name }}</span>
                                    </a>
                                </div>

                                <button class="absolute top-6 right-6 z-20 w-10 h-10 bg-black/40 backdrop-blur-sm rounded-full flex items-center justify-center text-red-500 hover:scale-110 transition-transform btn-wishlist" data-id="{{ $food->id }}">
                                    <svg class="w-5 h-5 heart-icon" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Mobile logout button -->
            <form method="POST" action="{{ route('logout') }}" class="mt-8 text-center md:hidden">
                @csrf
                <button type="submit" class="text-[12px] tracking-[0.1em] uppercase text-red-400 hover:text-red-300 transition-colors inline-flex items-center gap-2 px-4 py-2 border border-red-400/30 rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Đăng xuất
                </button>
            </form>
        </div>
    </div>

    <!-- Password Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-[#0a0d14]/90 hidden flex-col items-center justify-center backdrop-blur-sm z-[9999] opacity-0 transition-opacity duration-300" style="z-index: 9999;">
        <div class="bg-[#0a0d14] border border-primary/30 p-8 max-w-md w-full m-4 shadow-2xl rounded-xl relative transform scale-95 transition-transform duration-300" id="passwordModalContent" style="background-color: #0a0d14;">
            <button type="button" onclick="closeModal('passwordModal')" class="absolute top-6 right-6 text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-3xl font-sans font-semibold text-white mb-8 tracking-wide">Đổi mật khẩu</h3>
            <form method="POST" action="{{ route('profile.update_password') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Mật khẩu hiện tại</label>
                    <input type="password" name="old_password" required class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                </div>
                <div class="relative">
                    <div class="flex justify-between items-end mb-3">
                        <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase">Mật khẩu mới</label>
                        <button type="button" onclick="togglePasswordGenerator()" class="text-primary hover:text-white transition-colors flex items-center gap-1 text-[12px]" title="Tạo mật khẩu ngẫu nhiên">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Tạo tự động
                        </button>
                    </div>
                    <input type="password" name="password" id="newPasswordInput" required class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                    
                    <!-- Password Generator Panel -->
                    <div id="passwordGeneratorPanel" class="hidden mt-4 p-4 border border-primary/20 rounded-lg bg-black/50">
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-300">Độ dài: <span id="pwdLengthDisplay" class="font-bold text-primary">12</span></span>
                            </div>
                            <input type="range" id="pwdLengthSlider" min="8" max="32" value="12" class="w-full accent-primary" oninput="document.getElementById('pwdLengthDisplay').innerText = this.value; generateRandomPassword();">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mb-4 text-[13px] text-gray-300">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="pwdUppercase" checked class="accent-primary" onchange="generateRandomPassword()"> Ký tự hoa (A-Z)
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="pwdLowercase" checked class="accent-primary" onchange="generateRandomPassword()"> Ký tự thường (a-z)
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="pwdNumbers" checked class="accent-primary" onchange="generateRandomPassword()"> Ký tự số (0-9)
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="pwdSpecial" checked class="accent-primary" onchange="generateRandomPassword()"> Ký tự đặc biệt
                            </label>
                        </div>

                        <div class="flex items-center gap-2 mb-4">
                            <input type="text" id="generatedPassword" readonly class="flex-1 bg-[#0a0d14] border border-primary/30 text-white px-3 py-2 rounded focus:outline-none font-mono text-center tracking-widest text-sm">
                            <button type="button" onclick="generateRandomPassword()" class="w-10 h-10 border border-primary/30 rounded text-gray-400 hover:text-white hover:border-primary transition-colors flex items-center justify-center shrink-0" title="Tạo mới">
                                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 21 12 a 9 9 0 1 1 -9 -9 c 2.52 0 4.93 1 6.74 2.74 L 21 8"></path><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 21 3 v 5 h -5"></path></svg>
                            </button>
                            <button type="button" onclick="copyGeneratedPassword()" id="btnCopyPwd" class="w-10 h-10 border border-primary/30 rounded text-gray-400 hover:text-white hover:border-primary transition-colors flex items-center justify-center shrink-0" title="Copy">
                                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 5 15 L 4 15 A 2 2 0 0 1 2 13 L 2 4 A 2 2 0 0 1 4 2 L 13 2 A 2 2 0 0 1 15 4 L 15 5"></path></svg>
                            </button>
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-start gap-2 cursor-pointer text-[13px] text-yellow-400/90 font-medium leading-tight">
                                <input type="checkbox" id="pwdConfirmSaved" class="accent-yellow-400 w-4 h-4 mt-0.5" onchange="toggleApplyButton()">
                                Tôi xác nhận đã lưu lại mật khẩu mới này an toàn
                            </label>
                        </div>
                        
                        <button type="button" id="btnApplyPassword" disabled onclick="applyGeneratedPassword()" class="w-full bg-primary text-[#0a0d14] py-2 rounded text-[13px] font-bold tracking-[0.1em] uppercase transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            Áp dụng mật khẩu
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" id="confirmPasswordInput" required class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                </div>
                <div class="pt-8 flex justify-end gap-4 border-t border-primary/20 mt-6">
                    <button type="button" onclick="closeModal('passwordModal')" class="text-gray-400 hover:text-white px-6 py-2.5 rounded text-[13px] tracking-wider uppercase transition-colors hover:bg-white/5">Hủy</button>
                    <button type="submit" class="bg-primary text-[#0a0d14] px-8 py-2.5 rounded text-[13px] font-bold tracking-[0.1em] uppercase hover:bg-white transition-all duration-300 shadow-[0_0_15px_rgba(var(--primary-rgb),0.2)] hover:shadow-[0_0_25px_rgba(var(--primary-rgb),0.5)] transform hover:-translate-y-0.5">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CCCD Modal -->
    <div id="cccdModal" class="fixed inset-0 bg-[#0a0d14]/90 hidden flex-col items-center justify-center backdrop-blur-sm z-[9999] opacity-0 transition-opacity duration-300 overflow-y-auto" style="z-index: 9999;">
        <div class="bg-[#0a0d14] border border-primary/30 p-8 max-w-2xl w-full m-4 shadow-2xl rounded-xl relative my-auto transform scale-95 transition-transform duration-300" id="cccdModalContent" style="background-color: #0a0d14;">
            <button type="button" onclick="closeModal('cccdModal')" class="absolute top-6 right-6 text-gray-400 hover:text-white transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-3xl font-sans font-semibold text-white mb-8 tracking-wide">Cập nhật Căn cước công dân</h3>
            <form method="POST" action="{{ route('profile.update_cccd') }}" class="space-y-8">
                @csrf
                <input type="hidden" name="front" id="cccdFrontBase64">
                <input type="hidden" name="back" id="cccdBackBase64">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-4">
                    <!-- Mặt trước -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Ảnh CCCD Mặt Trước</label>
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
                        <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Ảnh CCCD Mặt Sau</label>
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
                        <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Số CCCD</label>
                        <input type="text" name="number" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Ngày cấp</label>
                        <input type="text" name="date" class="custom-datepicker w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors cursor-pointer" placeholder="Chọn ngày cấp">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Nơi cấp</label>
                        <input type="text" name="place" class="w-full bg-transparent border-b border-primary/30 focus:border-primary text-white px-0 py-2 text-base focus:outline-none transition-colors">
                    </div>
                </div>

                <div class="pt-8 flex justify-end gap-4 border-t border-primary/20 mt-6">
                    <button type="button" onclick="closeModal('cccdModal')" class="text-gray-400 hover:text-white px-6 py-2.5 rounded text-[13px] tracking-wider uppercase transition-colors hover:bg-white/5">Hủy</button>
                    <button type="submit" class="bg-primary text-[#0a0d14] px-8 py-2.5 rounded text-[13px] font-bold tracking-[0.1em] uppercase hover:bg-white transition-all duration-300 shadow-[0_0_15px_rgba(var(--primary-rgb),0.2)] hover:shadow-[0_0_25px_rgba(var(--primary-rgb),0.5)] transform hover:-translate-y-0.5">
                        Lưu CCCD
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View CCCD Modal -->
    <div id="viewCccdModal" class="fixed inset-0 bg-[#0a0d14]/90 hidden flex-col items-center justify-center backdrop-blur-sm z-[9999] opacity-0 transition-opacity duration-300 overflow-y-auto" style="z-index: 9999;">
        <div class="bg-[#0a0d14] border border-primary/30 p-8 max-w-4xl w-full m-4 shadow-2xl rounded-xl relative my-auto transform scale-95 transition-transform duration-300" id="viewCccdModalContent" style="background-color: #0a0d14;">
            <button type="button" onclick="closeModal('viewCccdModal')" class="absolute top-6 right-6 text-gray-400 hover:text-white transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-3xl font-sans font-semibold text-white mb-8 tracking-wide">Xem Căn cước công dân</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Mặt trước -->
                <div>
                    <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Mặt Trước</label>
                    <div class="h-64 border border-primary/30 rounded-lg flex items-center justify-center bg-black/50 overflow-hidden relative">
                        @php
                            $frontImg = $userInfo['cccd_front'] ?? $userInfo['front'] ?? null;
                            $backImg = $userInfo['cccd_back'] ?? $userInfo['back'] ?? null;
                            
                            $baseUrl = 'https://data.nks.vn/storage/';
                            if ($frontImg && !str_starts_with($frontImg, 'http')) {
                                $frontImg = $baseUrl . ltrim($frontImg, '/');
                            }
                            if ($backImg && !str_starts_with($backImg, 'http')) {
                                $backImg = $baseUrl . ltrim($backImg, '/');
                            }
                        @endphp
                        @if($frontImg)
                            <img src="{{ $frontImg }}" alt="CCCD Mặt trước" class="max-w-full max-h-full object-contain">
                        @else
                            <div class="text-gray-500 text-sm">Chưa có ảnh</div>
                        @endif
                    </div>
                </div>
                <!-- Mặt sau -->
                <div>
                    <label class="block text-[13px] font-semibold text-gray-400 tracking-[0.1em] uppercase mb-3">Mặt Sau</label>
                    <div class="h-64 border border-primary/30 rounded-lg flex items-center justify-center bg-black/50 overflow-hidden relative">
                        @if($backImg)
                            <img src="{{ $backImg }}" alt="CCCD Mặt sau" class="max-w-full max-h-full object-contain">
                        @else
                            <div class="text-gray-500 text-sm">Chưa có ảnh</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pt-8 flex justify-end">
                <button type="button" onclick="closeModal('viewCccdModal')" class="bg-primary/10 border border-primary text-primary px-8 py-2.5 rounded text-[13px] font-bold tracking-[0.1em] uppercase hover:bg-primary hover:text-[#0a0d14] transition-all duration-300">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div id="cropperModal" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden items-center justify-center" style="z-index: 9999;">
        <div class="border border-primary/20 p-6 rounded-lg w-full max-w-md relative animate-fade-in shadow-2xl shadow-primary/10" style="background-color: #0a0d14;">
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        // Modal Logic
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Trigger animation
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                if (content) {
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }
            });
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            
            // Trigger exit animation
            modal.classList.add('opacity-0');
            if (content) {
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
            }
            
            // Hide after animation completes
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        // Close modal on click outside
        window.addEventListener('click', function(event) {
            if (event.target.id === 'passwordModal') closeModal('passwordModal');
            if (event.target.id === 'cccdModal') closeModal('cccdModal');
            if (event.target.id === 'viewCccdModal') closeModal('viewCccdModal');
        });

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
        let tsProvince = null;
        let tsAdmin = null;

        function loadAdministratives(provinceId) {
            const adminSelect = document.getElementById('administrative_select');
            
            if (!provinceId) {
                if (tsAdmin) {
                    tsAdmin.clearOptions();
                    tsAdmin.clear();
                    tsAdmin.addOption({value: '', text: 'Chọn quận huyện'});
                } else {
                    adminSelect.innerHTML = '<option value="">Chọn quận huyện</option>';
                }
                return;
            }

            if (tsAdmin) {
                tsAdmin.clearOptions();
                tsAdmin.clear();
                tsAdmin.addOption({value: '', text: 'Đang tải...'});
            } else {
                adminSelect.innerHTML = '<option value="">Đang tải...</option>';
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
                if (tsAdmin) {
                    tsAdmin.clearOptions();
                    tsAdmin.clear();
                    tsAdmin.addOption({value: '', text: 'Chọn quận huyện'});
                    if (data && data.data) {
                        data.data.forEach(item => {
                            tsAdmin.addOption({value: item.id, text: item.title || item.name});
                        });
                    }
                    tsAdmin.refreshOptions(false);
                } else {
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
                }
            })
            .catch(err => {
                console.error('Lỗi khi tải quận huyện', err);
                if (tsAdmin) {
                    tsAdmin.clearOptions();
                    tsAdmin.clear();
                    tsAdmin.addOption({value: '', text: 'Lỗi tải dữ liệu'});
                } else {
                    adminSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                }
            });
        }

        // Initialize and Pre-load administratives
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Tom Select
            tsProvince = new TomSelect("#province_select", {
                create: false,
                onChange: function(value) {
                    loadAdministratives(value);
                }
            });

            tsAdmin = new TomSelect("#administrative_select", {
                create: false
            });

            const provSelect = document.getElementById('province_select');
            if (provSelect.value) {
                loadAdministratives(provSelect.value);
                // We don't want to clear the district if it was already selected previously.
                // Note: The loaded options will overwrite the current selected district if we aren't careful.
                // We should re-select the existing district if it exists.
                setTimeout(() => {
                    let existingDist = "{{ $userInfo['administrative'] ?? '' }}";
                    if(existingDist && tsAdmin) {
                        tsAdmin.setValue(existingDist);
                    }
                }, 500);
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
                    document.getElementById('cropperModal').classList.add('flex');
                    
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
            document.getElementById('cropperModal').classList.remove('flex');
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

        @if(isset($userInfo['email']) && isset($userInfo['avatar']) && $userInfo['avatar'])
        // Sync avatar to localStorage for the login page to use
        document.addEventListener('DOMContentLoaded', function() {
            let savedAvatars = JSON.parse(localStorage.getItem('saved_avatars')) || {};
            savedAvatars["{{ $userInfo['email'] }}"] = "{!! $userInfo['avatar'] !!}";
            localStorage.setItem('saved_avatars', JSON.stringify(savedAvatars));
        });
        @endif

        // Initialize flatpickr datepickers
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.custom-datepicker', {
                dateFormat: "Y-m-d",        // Sent to server
                altInput: true,             // Show an alternative text input for UX
                altFormat: "d/m/Y",         // Format shown to user
                allowInput: true,           // Allow user to type the date manually
                theme: "light",             // Matches the light theme of select options
                locale: {
                    firstDayOfWeek: 1,      // Start week on Monday
                    weekdays: {
                        shorthand: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                        longhand: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy']
                    },
                    months: {
                        shorthand: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                        longhand: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12']
                    }
                }
            });
        });

        // Password Generator Logic
        function togglePasswordGenerator() {
            const panel = document.getElementById('passwordGeneratorPanel');
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden')) {
                generateRandomPassword();
            }
        }

        function generateRandomPassword() {
            const length = document.getElementById('pwdLengthSlider').value;
            const useUpper = document.getElementById('pwdUppercase').checked;
            const useLower = document.getElementById('pwdLowercase').checked;
            const useNums = document.getElementById('pwdNumbers').checked;
            const useSpec = document.getElementById('pwdSpecial').checked;

            if (!useUpper && !useLower && !useNums && !useSpec) {
                document.getElementById('pwdUppercase').checked = true;
                return generateRandomPassword();
            }

            const upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const lowerChars = "abcdefghijklmnopqrstuvwxyz";
            const numChars = "0123456789";
            const specChars = "!@#$%^&*()_+~`|}{[]:;?><,./-=";

            let charPool = "";
            let password = "";

            if (useUpper) { charPool += upperChars; password += upperChars[Math.floor(Math.random() * upperChars.length)]; }
            if (useLower) { charPool += lowerChars; password += lowerChars[Math.floor(Math.random() * lowerChars.length)]; }
            if (useNums) { charPool += numChars; password += numChars[Math.floor(Math.random() * numChars.length)]; }
            if (useSpec) { charPool += specChars; password += specChars[Math.floor(Math.random() * specChars.length)]; }

            while (password.length < length) {
                password += charPool[Math.floor(Math.random() * charPool.length)];
            }

            password = password.split('').sort(() => 0.5 - Math.random()).join('');
            document.getElementById('generatedPassword').value = password;
            
            // Reset confirm checkbox when a new password is generated
            document.getElementById('pwdConfirmSaved').checked = false;
            toggleApplyButton();
            
            // Reset copy button styling
            const btnCopy = document.getElementById('btnCopyPwd');
            btnCopy.classList.remove('text-green-400', 'border-green-400');
            btnCopy.innerHTML = '<svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 5 15 L 4 15 A 2 2 0 0 1 2 13 L 2 4 A 2 2 0 0 1 4 2 L 13 2 A 2 2 0 0 1 15 4 L 15 5"></path></svg>';
        }

        function copyGeneratedPassword() {
            const pwdInput = document.getElementById('generatedPassword');
            if (!pwdInput.value) return;
            
            pwdInput.select();
            pwdInput.setSelectionRange(0, 99999); // For mobile devices
            
            navigator.clipboard.writeText(pwdInput.value).then(() => {
                const btnCopy = document.getElementById('btnCopyPwd');
                btnCopy.classList.add('text-green-400', 'border-green-400');
                btnCopy.innerHTML = '<svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 5 13 l 4 4 L 19 7"></path></svg>';
                setTimeout(() => {
                    btnCopy.classList.remove('text-green-400', 'border-green-400');
                    btnCopy.innerHTML = '<svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 5 15 L 4 15 A 2 2 0 0 1 2 13 L 2 4 A 2 2 0 0 1 4 2 L 13 2 A 2 2 0 0 1 15 4 L 15 5"></path></svg>';
                }, 2000);
            });
        }

        function toggleApplyButton() {
            const isConfirmed = document.getElementById('pwdConfirmSaved').checked;
            document.getElementById('btnApplyPassword').disabled = !isConfirmed;
        }

        function applyGeneratedPassword() {
            const pwd = document.getElementById('generatedPassword').value;
            if (pwd) {
                document.getElementById('newPasswordInput').value = pwd;
                document.getElementById('confirmPasswordInput').value = pwd;
                togglePasswordGenerator(); // hide panel
            }
        }
    </script>
    @endpush
</x-layouts.app>