@extends('layouts.utility')

@section('title', 'Vòng Quay Ăn Trưa - Hôm nay ăn gì? - WebApp Bắc Ninh')
@section('meta_description', 'Vòng quay may mắn chọn món ăn trưa. Chế độ "Đầu tháng sang chảnh" và "Cuối tháng bần hàn". Quay ngay để biết trưa nay ăn gì!')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 font-weight-bold text-primary mb-3">Vòng Quay "Thần Thánh"</h1>
            <p class="lead text-muted mb-5">Đừng để câu hỏi "Trưa nay ăn gì?" làm chia rẽ tình đồng nghiệp!</p>

            <!-- Controls -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Chọn tình trạng ví tiền của bạn:</h5>
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-outline-success active" onclick="setMode('rich')">
                            <input type="radio" name="mode" id="option1" checked> 💰 Đầu tháng (Giàu)
                        </label>
                        <label class="btn btn-outline-secondary" onclick="setMode('normal')">
                            <input type="radio" name="mode" id="option2"> 😐 Giữa tháng (Đủ)
                        </label>
                        <label class="btn btn-outline-danger" onclick="setMode('poor')">
                            <input type="radio" name="mode" id="option3"> 💸 Cuối tháng (Nghèo)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Wheel Container -->
            <div class="position-relative d-inline-block mb-5" style="max-width: 100%; overflow: hidden;">
                <!-- Arrow -->
                <div class="position-absolute" style="top: -10px; left: 50%; transform: translateX(-50%); z-index: 10; width: 0; height: 0; border-left: 20px solid transparent; border-right: 20px solid transparent; border-top: 40px solid #dc3545;"></div>
                
                <canvas id="canvas" width="500" height="500" style="width: 100%; max-width: 400px; height: auto;"></canvas>
                
                <!-- Spin Button Center (Optional style) -->
                <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 60px; height: 60px; background: #fff; border-radius: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; font-weight: bold; cursor: pointer; user-select: none;" onclick="spin()">
                    QUAY
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary btn-lg px-5 font-weight-bold shadow-lg spin-btn" onclick="spin()">
                    <i class="fas fa-sync-alt mr-2"></i> QUAY NGAY
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <h3 class="text-uppercase text-muted mb-3">Trưa nay bạn sẽ ăn:</h3>
                <h1 class="display-4 font-weight-bold text-primary mb-4" id="resultName">...</h1>
                
                <div id="resultImageContainer" class="mb-4">
                    <!-- Meme will be inserted here -->
                </div>

                <div class="alert alert-light border border-light shadow-sm" id="resultDesc">
                    Chúc bạn ngon miệng!
                </div>

                <button type="button" class="btn btn-outline-primary mt-3" data-dismiss="modal">Quay lại</button>
                <button type="button" class="btn btn-success mt-3 ml-2" onclick="shareResult()">
                    <i class="fab fa-facebook"></i> Khoe ngay
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Configuration
    const modes = {
        rich: [
            { text: "Pizza", color: "#FF5733", meme: "https://media.giphy.com/media/STH1UmoVpxTnXqS9E3/giphy.gif" }, // Pizza
            { text: "Sushi", color: "#C70039", meme: "https://media.giphy.com/media/13CoXDiaCcCzdK/giphy.gif" }, // Sushi
            { text: "Lẩu Haidilao", color: "#900C3F", meme: "https://media.giphy.com/media/l2Je3qSgD6I9EN0LC/giphy.gif" }, // Hotpot
            { text: "Buffet Sen", color: "#581845", meme: "https://media.giphy.com/media/wZtxnyxZwRMZi/giphy.gif" }, // Buffet
            { text: "Steak", color: "#FFC300", meme: "https://media.giphy.com/media/3o7TKnKXMdlljrThwk/giphy.gif" }, // Steak
            { text: "Starbucks", color: "#DAF7A6", meme: "https://media.giphy.com/media/M4trH1uta8OPu/giphy.gif" }  // Drink
        ],
        normal: [
            { text: "Cơm Rang", color: "#FFC300", meme: null },
            { text: "Bún Chả", color: "#DAF7A6", meme: null },
            { text: "Phở Bò", color: "#FF5733", meme: null },
            { text: "Bún Đậu", color: "#33FF57", meme: null },
            { text: "Cơm Tấm", color: "#3357FF", meme: null },
            { text: "Bánh Mì", color: "#FF33F6", meme: null }
        ],
        poor: [
            { text: "Mì Tôm", color: "#FF5733", meme: "https://media.giphy.com/media/12uXi1GXBibALC/giphy.gif" },
            { text: "Bánh Mì Không", color: "#C70039", meme: "https://media.giphy.com/media/3o6Zt44rW4tH9YtCne/giphy.gif" },
            { text: "Xôi 5k", color: "#900C3F", meme: null },
            { text: "Nhịn Ăn", color: "#000000", text_color:"#fff", meme: "https://media.giphy.com/media/26ufcVAp3AiJJsrIs/giphy.gif" }, // Crying
            { text: "Uống Nước", color: "#33C4FF", meme: "https://media.giphy.com/media/3o7TKSjRrfPHj3unx6/giphy.gif" },
            { text: "Gói Hảo Hảo", color: "#FFC300", meme: null }
        ]
    };

    let options = modes.rich;

    let startAngle = 0;
    let arc = Math.PI / (options.length / 2);
    let spinTimeout = null;
    let spinArcStart = 10;
    let spinTime = 0;
    let spinTimeTotal = 0;
    let ctx;

    function setMode(mode) {
        options = modes[mode];
        arc = Math.PI / (options.length / 2);
        drawRouletteWheel();
    }

    function byte2Hex(n) {
        var nybHexString = "0123456789ABCDEF";
        return String(nybHexString.substr((n >> 4) & 0x0F,1)) + nybHexString.substr(n & 0x0F,1);
    }

    function RGBZC(a) {
        return '#' + byte2Hex(a);
    }

    function drawRouletteWheel() {
        var canvas = document.getElementById("canvas");
        if (canvas.getContext) {
            var outsideRadius = 200;
            var textRadius = 160;
            var insideRadius = 50;

            ctx = canvas.getContext("2d");
            ctx.clearRect(0,0,500,500);

            ctx.strokeStyle = "white";
            ctx.lineWidth = 2;

            ctx.font = 'bold 16px Inter, sans-serif'; // Improved font

            for(var i = 0; i < options.length; i++) {
                var angle = startAngle + i * arc;
                //ctx.fillStyle = colors[i];
                ctx.fillStyle = options[i].color;

                ctx.beginPath();
                ctx.arc(250, 250, outsideRadius, angle, angle + arc, false);
                ctx.arc(250, 250, insideRadius, angle + arc, angle, true);
                ctx.stroke();
                ctx.fill();

                ctx.save();
                ctx.shadowOffsetX = -1;
                ctx.shadowOffsetY = -1;
                ctx.shadowBlur    = 0;
                //ctx.shadowColor   = "rgb(220,220,220)";
                ctx.fillStyle = options[i].text_color || "white";
                ctx.translate(250 + Math.cos(angle + arc / 2) * textRadius, 
                            250 + Math.sin(angle + arc / 2) * textRadius);
                ctx.rotate(angle + arc / 2 + Math.PI / 2);
                var text = options[i].text;
                ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
                ctx.restore();
            } 

            //Arrow
            /*
            ctx.fillStyle = "black";
            ctx.beginPath();
            ctx.moveTo(250 - 4, 250 - (outsideRadius + 5));
            ctx.lineTo(250 + 4, 250 - (outsideRadius + 5));
            ctx.lineTo(250 + 4, 250 - (outsideRadius - 5));
            ctx.lineTo(250 + 9, 250 - (outsideRadius - 5));
            ctx.lineTo(250 + 0, 250 - (outsideRadius - 13));
            ctx.lineTo(250 - 9, 250 - (outsideRadius - 5));
            ctx.lineTo(250 - 4, 250 - (outsideRadius - 5));
            ctx.lineTo(250 - 4, 250 - (outsideRadius + 5));
            ctx.fill();
            */
        }
    }

    function spin() {
        spinAngleStart = Math.random() * 10 + 10;
        spinTime = 0;
        spinTimeTotal = Math.random() * 3 + 4 * 1000;
        rotateWheel();
        $('.spin-btn').prop('disabled', true);
    }

    function rotateWheel() {
        spinTime += 30;
        if(spinTime >= spinTimeTotal) {
            stopRotateWheel();
            return;
        }
        var spinAngle = spinAngleStart - easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
        startAngle += (spinAngle * Math.PI / 180);
        drawRouletteWheel();
        spinTimeout = setTimeout('rotateWheel()', 30);
    }

    function stopRotateWheel() {
        clearTimeout(spinTimeout);
        var degrees = startAngle * 180 / Math.PI + 90;
        var arcd = arc * 180 / Math.PI;
        var index = Math.floor((360 - degrees % 360) / arcd);
        ctx.save();
        ctx.font = 'bold 30px sans-serif';
        var text = options[index].text;
        showResult(options[index]);
        //ctx.fillText(text, 250 - ctx.measureText(text).width / 2, 250 + 10);
        ctx.restore();
        $('.spin-btn').prop('disabled', false);
    }

    function easeOut(t, b, c, d) {
        var ts = (t/=d)*t;
        var tc = ts*t;
        return b+c*(tc + -3*ts + 3*t);
    }

    function showResult(item) {
        $('#resultName').text(item.text);
        
        let html = '';
        if(item.meme) {
            html = `<img src="${item.meme}" class="img-fluid rounded" style="max-height: 200px;">`;
            $('#resultDesc').text(item.text === "Nhịn Ăn" ? "Chia buồn cùng bạn! Nghèo thì phải chấp nhận thôi..." : "Chúc mừng bạn đã quay trúng " + item.text);
            if(item.text === "Nhịn Ăn" || item.text === "Mì Tôm") {
                $('#resultDesc').addClass('alert-danger').removeClass('alert-light');
            } else {
                 $('#resultDesc').addClass('alert-success').removeClass('alert-danger alert-light');
            }
        } else {
            html = '<i class="fas fa-utensils fa-5x text-secondary"></i>';
            $('#resultDesc').removeClass('alert-danger alert-success').addClass('alert-light').text('Hôm nay ăn ' + item.text + ' nhé!');
        }
        
        $('#resultImageContainer').html(html);
        
        // Use native Bootstrap 5 Modal
        var myModal = new bootstrap.Modal(document.getElementById('resultModal'));
        myModal.show();
    }

    function shareResult() {
        // Simple share logic, implies copying text or opening FB share (dummy)
        alert('Đã copy kết quả vào clipboard! Hãy dán lên Facebook ngay!');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ === 'undefined') {
            console.error('jQuery is not loaded');
            return;
        }
        $(document).ready(function() {
            drawRouletteWheel();
        });
    });
</script>
@endpush
@endsection
