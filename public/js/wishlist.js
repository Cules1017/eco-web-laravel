document.addEventListener('DOMContentLoaded', function() {
    const WISHLIST_KEY = 'venshop_wishlist';
    
    // Khởi tạo wishlist từ localStorage
    let wishlist = JSON.parse(localStorage.getItem(WISHLIST_KEY)) || [];
    
    // Cập nhật số lượng trên header
    function updateBadge() {
        const badge = document.getElementById('wishlist-badge');
        if (badge) {
            if (wishlist.length > 0) {
                badge.textContent = wishlist.length;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }
    
    // Lưu vào localStorage
    function saveWishlist() {
        localStorage.setItem(WISHLIST_KEY, JSON.stringify(wishlist));
        updateBadge();
        updateHeartIcons();
    }
    
    // Toggle sản phẩm yêu thích
    window.toggleWishlist = function(id, name, price, image, url) {
        const index = wishlist.findIndex(item => item.id == id);
        if (index > -1) {
            // Xóa khỏi wishlist
            wishlist.splice(index, 1);
            showToast('Đã bỏ sản phẩm khỏi danh sách yêu thích', 'info');
        } else {
            // Thêm vào wishlist
            wishlist.push({ id, name, price, image, url });
            showToast('Đã thêm sản phẩm vào danh sách yêu thích', 'success');
        }
        saveWishlist();
        
        // Nếu đang ở trang wishlist thì render lại
        if (document.getElementById('wishlist-container')) {
            renderWishlistPage();
        }
    };
    
    // Hiển thị thông báo (toast)
    function showToast(message, type) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 2500
            });
        } else {
            alert(message);
        }
    }
    
    // Cập nhật trạng thái icon trái tim trên toàn trang
    function updateHeartIcons() {
        document.querySelectorAll('.btn-wishlist').forEach(btn => {
            const id = btn.getAttribute('data-id');
            const icon = btn.querySelector('i');
            if (wishlist.some(item => item.id == id)) {
                icon.classList.remove('far');
                icon.classList.add('fas', 'text-danger');
            } else {
                icon.classList.remove('fas', 'text-danger');
                icon.classList.add('far');
            }
        });
    }
    
    // Render trang danh sách yêu thích
    window.renderWishlistPage = function() {
        const container = document.getElementById('wishlist-container');
        if (!container) return;
        
        if (wishlist.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="far fa-heart fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Danh sách yêu thích trống</h4>
                    <p>Bạn chưa lưu sản phẩm nào vào danh sách yêu thích.</p>
                    <a href="/products" class="btn btn-outline-dark mt-3 px-4 py-2 text-uppercase fw-bold">Tiếp tục mua sắm</a>
                </div>
            `;
            return;
        }
        
        let html = '<div class="row g-4">';
        wishlist.forEach(item => {
            html += `
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm product-card position-relative transition-all" style="border-radius: 12px; overflow: hidden;">
                        <button onclick="toggleWishlist('${item.id}')" class="btn position-absolute top-0 end-0 m-2 bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0; z-index: 2; border: none; border-radius: 50%;">
                            <i class="fas fa-times text-muted"></i>
                        </button>
                        <a href="${item.url}" class="text-decoration-none">
                            <img src="${item.image}" class="card-img-top bg-light" alt="${item.name}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title text-dark mb-2" style="font-weight: 500; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">${item.name}</h6>
                                <div class="fw-bold text-danger fs-5">${Number(item.price).toLocaleString('vi-VN')} đ</div>
                            </div>
                        </a>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    };
    
    // Khởi chạy khi load trang
    updateBadge();
    updateHeartIcons();
    
    if (document.getElementById('wishlist-container')) {
        renderWishlistPage();
    }
});
