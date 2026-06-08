<x-layouts.app>
    @push('styles')
    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
    <style>
        .contact-map-container {
            width: 100%;
            height: 450px;
            filter: grayscale(100%) invert(90%);
            margin-bottom: 60px;
        }
        #map {
            width: 100%;
            height: 100%;
        }
        
        .primary-text {
            color: #0077bb;
        }
        
        .contact-input {
            width: 100%;
            background: transparent;
            border: 1px solid rgba(0, 119, 187, 0.3);
            color: #fff;
            padding: 15px 20px;
            margin-bottom: 20px;
            font-family: var(--font-sans, 'Jost', sans-serif);
            font-size: 13px;
            letter-spacing: 0.1em;
            transition: border-color 0.3s;
        }
        .contact-input:focus {
            outline: none;
            border-color: #0077bb;
        }
        ::placeholder {
            color: #999;
        }
        
        .btn-primary {
            background: transparent;
            border: 1px solid #0077bb;
            color: #fff;
            padding: 12px 30px;
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #0077bb;
            color: #fff;
        }
        
        .address-block {
            margin-bottom: 40px;
            text-align: center;
        }
        .address-title {
            color: #0077bb;
            font-family: var(--font-sans, 'Jost', sans-serif);
            font-size: 12px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .address-text {
            color: #999;
            font-family: var(--font-sans, 'Jost', sans-serif);
            font-size: 14px;
            line-height: 1.8;
            letter-spacing: 0.05em;
        }
        .script-font {
            font-family: 'Great Vibes', cursive;
            color: #0077bb;
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Great Vibes&display=swap" rel="stylesheet">
    @endpush

    <div class="bg-[#0b0e11] min-h-screen">

        <!-- Contact Content Section -->
        <div class="container mx-auto px-6 md:px-[60px] pt-[120px] pb-24 max-w-[1200px]">
            
            <!-- Top Map Section -->
            <div class="contact-map-container">
                <div id="map"></div>
            </div>
            
            <!-- Title -->
            <div class="text-center mb-20 flex flex-col items-center">
                <span class="script-font">Write to us</span>
                <div class="flex items-center gap-6">
                    <h1 class="text-3xl md:text-4xl font-serif text-[#0077bb] tracking-[0.2em] uppercase">LIÊN HỆ</h1>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 lg:gap-24">
                
                <!-- Form Column (Left) -->
                <div>
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã liên hệ!');">
                        <input type="text" placeholder="Name" class="contact-input" required>
                        <input type="email" placeholder="E-mail" class="contact-input" required>
                        <textarea placeholder="Message" class="contact-input min-h-[250px] resize-y" required></textarea>
                        
                        <div class="mt-4 flex justify-center md:justify-start">
                            <button type="submit" class="btn-primary">
                                SEND
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Addresses Column (Right) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-12">
                    
                    <div class="address-block">
                        <h3 class="address-title">HÀ NỘI</h3>
                        <div class="address-text">
                            <p>123 Đường Láng, Đống Đa</p>
                            <p>024-333-4444</p>
                            <p>hanoi@qrorder.vn</p>
                        </div>
                    </div>
                    
                    <div class="address-block">
                        <h3 class="address-title">HỒ CHÍ MINH</h3>
                        <div class="address-text">
                            <p>456 Nguyễn Thị Minh Khai, Q1</p>
                            <p>028-555-6666</p>
                            <p>hcm@qrorder.vn</p>
                        </div>
                    </div>
                    
                    <div class="address-block">
                        <h3 class="address-title">ĐÀ NẴNG</h3>
                        <div class="address-text">
                            <p>789 Bạch Đằng, Hải Châu</p>
                            <p>0236-777-8888</p>
                            <p>danang@qrorder.vn</p>
                        </div>
                    </div>
                    
                    <div class="address-block">
                        <h3 class="address-title">CẦN THƠ</h3>
                        <div class="address-text">
                            <p>101 Ninh Kiều, Cần Thơ</p>
                            <p>0292-999-0000</p>
                            <p>cantho@qrorder.vn</p>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
        
    </div>

    @push('scripts')
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var map = new maplibregl.Map({
                container: 'map',
                style: 'https://basemaps.cartocdn.com/gl/dark-matter-gl-style/style.json',
                center: [105.804817, 21.028511], // Hanoi coordinates
                zoom: 12,
                scrollZoom: false
            });

            map.addControl(new maplibregl.NavigationControl());

            // Using default marker
            new maplibregl.Marker({ color: "#0077bb" })
                .setLngLat([105.804817, 21.028511])
                .addTo(map);
        });
    </script>
    @endpush
</x-layouts.app>
