@extends('layouts.eshopper')

@section('title', 'Mini Game - Trả lời đúng, Trúng Voucher')

@push('styles')
<style>
    .game-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 30px;
        background: #f8f9fa;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .streak-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .streak-star {
        font-size: 30px;
        color: #ccc;
        transition: color 0.3s;
    }
    
    .streak-star.active {
        color: #ffd700;
    }

    #question-container {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .answer-btn {
        width: 120px;
        margin: 10px;
        font-size: 18px;
        border-radius: 25px;
    }

    #jar-container {
        display: none;
        margin: 30px 0;
        cursor: pointer;
    }
    
    .jar-image {
        font-size: 120px;
        color: #e53935;
        display: inline-block;
        animation: shake 0s infinite;
        text-shadow: 2px 4px 10px rgba(0,0,0,0.1);
    }
    
    .jar-image.shaking {
        animation: shake 0.5s infinite;
    }
    
    @keyframes shake {
        0% { transform: translate(1px, 1px) rotate(0deg); }
        10% { transform: translate(-1px, -2px) rotate(-1deg); }
        20% { transform: translate(-3px, 0px) rotate(1deg); }
        30% { transform: translate(3px, 2px) rotate(0deg); }
        40% { transform: translate(1px, -1px) rotate(1deg); }
        50% { transform: translate(-1px, 2px) rotate(-1deg); }
        60% { transform: translate(-3px, 1px) rotate(0deg); }
        70% { transform: translate(3px, 1px) rotate(-1deg); }
        80% { transform: translate(-1px, -1px) rotate(1deg); }
        90% { transform: translate(1px, 2px) rotate(0deg); }
        100% { transform: translate(1px, -2px) rotate(-1deg); }
    }

    #voucher-result {
        display: none;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        padding: 40px 20px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        margin-top: 20px;
        position: relative;
        overflow: hidden;
        animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    @keyframes popIn {
        0% { opacity: 0; transform: scale(0.5) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    
    .voucher-code {
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        letter-spacing: 4px;
        margin: 20px 0;
        padding: 15px 30px;
        background: rgba(255,255,255,0.2);
        border: 2px dashed rgba(255,255,255,0.6);
        border-radius: 12px;
        display: inline-block;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }

    #voucher-result p {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 20px;
    }
    
    .btn-copy {
        background: #fff;
        color: #059669;
        font-weight: 600;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .btn-copy:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="game-container">
        <h2>🌱 Eco Quiz - Lắc Hũ Nhận Quà</h2>
        <p class="text-muted">Trả lời đúng {{ $streakRequired }} câu liên tiếp để nhận lượt lắc hũ!</p>
        
        <div class="d-flex justify-content-between mb-3">
            <span>Lượt chơi còn lại: <strong id="lbl-questions-available">{{ $questionsAvailable }}</strong></span>
            <span>Chuỗi đúng: <strong id="lbl-current-streak">{{ $session->correct_streak }}</strong>/{{ $streakRequired }}</span>
        </div>

        <div class="streak-container" id="streak-stars">
            @for($i = 1; $i <= $streakRequired; $i++)
                <i class="fas fa-star streak-star {{ $i <= $session->correct_streak ? 'active' : '' }}" id="star-{{ $i }}"></i>
            @endfor
        </div>

        <div id="play-area">
            @if($questionsAvailable > 0)
                <div id="question-container">
                    <div id="loading" style="display:none;">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="question-content">
                        <p class="fs-4" id="question-text">Nhấn 'Bắt đầu' để lấy câu hỏi.</p>
                        <input type="hidden" id="current-question-id">
                    </div>
                </div>

                <div id="action-buttons">
                    <button class="btn btn-success" id="btn-start">Bắt đầu</button>
                </div>
                
                <div id="answer-buttons" style="display:none;">
                    <button class="btn btn-outline-success answer-btn" onclick="submitAnswer(1)">Đúng</button>
                    <button class="btn btn-outline-danger answer-btn" onclick="submitAnswer(0)">Sai</button>
                </div>
                
                <div id="result-message" class="mt-3" style="display:none;"></div>
            @else
                <div class="alert alert-warning">
                    Bạn đã hết lượt chơi hôm nay. Hãy mua sắm để nhận thêm lượt nhé!
                </div>
            @endif
        </div>

        <div id="jar-container">
            <h4 class="text-success mb-3">Bạn đã đủ điều kiện! Nhấn vào hũ để lắc!</h4>
            <i class="fas fa-gift jar-image" id="jar-img" onclick="shakeJar()"></i>
        </div>
        
        <div id="voucher-result">
            <h3 class="fw-bold mb-2" id="win-message">🎉 Chúc mừng bạn trúng thưởng!</h3>
            <p>Phần thưởng đã sẵn sàng cho đơn hàng tiếp theo.</p>
            <div class="voucher-code" id="win-code">ECO123</div>
            <p>Giảm ngay <strong id="win-value" class="fs-5"></strong></p>
            <button class="btn-copy" onclick="copyVoucher()">
                <i class="fas fa-copy me-2"></i>Sao chép mã
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    const streakRequired = {{ $streakRequired }};
    let currentStreak = {{ $session->correct_streak }};
    let isShaking = false;
    
    // Auto show jar if already have enough streak
    if (currentStreak >= streakRequired) {
        showJar();
    }
    
    document.getElementById('btn-start')?.addEventListener('click', fetchQuestion);

    function fetchQuestion() {
        document.getElementById('btn-start').style.display = 'none';
        document.getElementById('result-message').style.display = 'none';
        document.getElementById('question-content').style.display = 'none';
        document.getElementById('loading').style.display = 'block';
        
        fetch('{{ route('game.question') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            if (data.error) {
                Swal.fire('Lỗi', data.error, 'error');
                return;
            }
            
            document.getElementById('current-question-id').value = data.id;
            document.getElementById('question-text').innerText = data.question;
            document.getElementById('question-content').style.display = 'block';
            document.getElementById('answer-buttons').style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
        });
    }

    function submitAnswer(answer) {
        const questionId = document.getElementById('current-question-id').value;
        document.getElementById('answer-buttons').style.display = 'none';
        
        fetch('{{ route('game.answer') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                question_id: questionId,
                answer: answer
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                Swal.fire('Lỗi', data.error, 'error');
                return;
            }
            
            updateStreakUI(data.correct_streak);
            document.getElementById('lbl-questions-available').innerText = data.questions_available;
            
            const resDiv = document.getElementById('result-message');
            resDiv.style.display = 'block';
            
            if (data.is_correct) {
                resDiv.className = 'mt-3 alert alert-success';
                resDiv.innerHTML = '<strong>Chính xác!</strong> ' + (data.explanation ? data.explanation : '');
            } else {
                resDiv.className = 'mt-3 alert alert-danger';
                resDiv.innerHTML = '<strong>Sai rồi!</strong> Streak đã bị reset. ' + (data.explanation ? data.explanation : '');
            }
            
            if (data.can_shake) {
                setTimeout(showJar, 1500);
            } else if (data.questions_available > 0) {
                document.getElementById('btn-start').style.display = 'inline-block';
                document.getElementById('btn-start').innerText = 'Câu tiếp theo';
            } else {
                resDiv.innerHTML += '<br>Bạn đã hết lượt chơi hôm nay.';
            }
        });
    }

    function updateStreakUI(streak) {
        currentStreak = streak;
        document.getElementById('lbl-current-streak').innerText = streak;
        
        for (let i = 1; i <= streakRequired; i++) {
            const star = document.getElementById('star-' + i);
            if (i <= streak) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        }
    }

    function showJar() {
        document.getElementById('play-area').style.display = 'none';
        document.getElementById('jar-container').style.display = 'block';
    }

    function shakeJar() {
        if (isShaking) return;
        isShaking = true;
        
        const jarImg = document.getElementById('jar-img');
        jarImg.classList.add('shaking');
        
        // Shake for 2 seconds then call API
        setTimeout(() => {
            fetch('{{ route('game.shake') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                jarImg.classList.remove('shaking');
                isShaking = false;
                
                // Reset UI streak
                updateStreakUI(0);
                
                if (data.success && data.voucher) {
                    // Won
                    document.getElementById('jar-container').style.display = 'none';
                    const resDiv = document.getElementById('voucher-result');
                    resDiv.style.display = 'block';
                    
                    document.getElementById('win-message').innerText = data.message;
                    document.getElementById('win-code').innerText = data.voucher.code;
                    
                    const valText = data.voucher.discount_type === 'percent' 
                        ? data.voucher.discount_value + '%' 
                        : new Intl.NumberFormat('vi-VN').format(data.voucher.discount_value) + ' VNĐ';
                    document.getElementById('win-value').innerText = valText;
                    
                    // Fire Confetti!
                    var duration = 3 * 1000;
                    var end = Date.now() + duration;

                    (function frame() {
                        confetti({
                            particleCount: 5,
                            angle: 60,
                            spread: 55,
                            origin: { x: 0 },
                            colors: ['#10b981', '#ffffff', '#fbbf24']
                        });
                        confetti({
                            particleCount: 5,
                            angle: 120,
                            spread: 55,
                            origin: { x: 1 },
                            colors: ['#10b981', '#ffffff', '#fbbf24']
                        });

                        if (Date.now() < end) {
                            requestAnimationFrame(frame);
                        }
                    }());
                } else {
                    // Lost
                    Swal.fire({
                        title: 'Rất tiếc!',
                        text: data.message || 'Chúc bạn may mắn lần sau!',
                        icon: 'info',
                        confirmButtonText: 'Đóng'
                    });
                    
                    // Go back to play area if still has questions
                    document.getElementById('jar-container').style.display = 'none';
                    const qAvail = parseInt(document.getElementById('lbl-questions-available').innerText);
                    
                    document.getElementById('play-area').style.display = 'block';
                    if (qAvail > 0) {
                        document.getElementById('btn-start').style.display = 'inline-block';
                        document.getElementById('btn-start').innerText = 'Chơi tiếp';
                        document.getElementById('result-message').style.display = 'none';
                        document.getElementById('question-content').style.display = 'none';
                    }
                }
            });
        }, 2000);
    }
    
    function copyVoucher() {
        const code = document.getElementById('win-code').innerText;
        navigator.clipboard.writeText(code).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Đã sao chép mã voucher!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }
</script>
@endpush
