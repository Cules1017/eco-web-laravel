# Tài liệu Thiết kế Hệ thống - Eco Web Laravel

Tài liệu này cung cấp cái nhìn chuyên sâu và toàn diện về hệ thống website thương mại điện tử Eco Web, bao gồm cấu trúc cơ sở dữ liệu, phân tích đặc tả chức năng chi tiết theo từng luồng nghiệp vụ và kiến trúc cài đặt hệ thống.

---

## 1. Cấu trúc Cơ sở dữ liệu (Database Structure)
Hệ thống sử dụng cơ sở dữ liệu quan hệ với các bảng chính sau:

### 1.1. Bảng `users` (Người dùng)
Lưu trữ thông tin tài khoản của khách hàng và quản trị viên.
- `id`: Khóa chính.
- `name`, `first_name`, `last_name`: Họ và tên người dùng.
- `username`: Tên đăng nhập (unique).
- `email`: Địa chỉ email (unique) dùng để đăng nhập.
- `phone`: Số điện thoại liên hệ.
- `password`: Mật khẩu đã mã hóa (Bcrypt).
- `is_admin`: Boolean định danh quyền truy cập (true: Quản trị viên, false: Khách hàng).
- `email_verified_at`: Thời gian xác thực email.

### 1.2. Bảng `categories` (Danh mục sản phẩm)
- `id`: Khóa chính.
- `name`: Tên danh mục.
- `slug`: Đường dẫn thân thiện (unique) tối ưu SEO.
- `description`: Mô tả danh mục.
- `image`: Hình ảnh đại diện danh mục.
- `is_active`: Trạng thái hiển thị (ẩn/hiện).
- `order`: Số nguyên định dạng thứ tự ưu tiên hiển thị.
- `parent_id`: Khóa ngoại trỏ đến `categories.id` (hỗ trợ phân cấp danh mục cha - con).

### 1.3. Bảng `products` (Sản phẩm)
- `id`: Khóa chính.
- `category_id`: Khóa ngoại liên kết với bảng `categories`.
- `name`: Tên sản phẩm.
- `slug`: Đường dẫn thân thiện (unique).
- `description`: Mô tả chi tiết sản phẩm.
- `price`: Giá bán (kiểu decimal, hỗ trợ số thập phân).
- `stock`: Số lượng tồn kho.
- `image`: Hình ảnh sản phẩm (có thể lưu link ảnh chính).
- `is_active`: Trạng thái hiển thị sản phẩm ra frontend.
- `is_featured`: Đánh dấu sản phẩm nổi bật (để đưa ra các section đặc biệt ở trang chủ).

### 1.4. Bảng `addresses` (Sổ địa chỉ giao hàng)
- `id`: Khóa chính.
- `user_id`: Khóa ngoại liên kết với bảng `users` (Một user có thể có nhiều địa chỉ).
- `full_name`: Họ tên người nhận hàng.
- `phone`: Số điện thoại người nhận.
- `address`: Địa chỉ chi tiết (số nhà, tên đường).
- `province_name`, `district_name`, `ward_name`: Đơn vị hành chính cấp Tỉnh, Huyện, Xã.
- `is_default`: Đánh dấu địa chỉ mặc định để tự động điền khi thanh toán.

### 1.5. Bảng `orders` (Đơn đặt hàng)
Lưu trữ thông tin tổng quan của một phiên mua hàng.
- `id`: Khóa chính.
- `user_id`: Khóa ngoại liên kết với bảng `users`.
- `order_number`: Mã tra cứu đơn hàng (unique, sinh ngẫu nhiên ví dụ: ECO-123456).
- `status`: Trạng thái đơn hàng (`pending`: Chờ xử lý, `processing`: Đang xử lý, `shipping`: Đang vận chuyển, `completed`: Hoàn thành, `cancelled`: Đã hủy).
- `payment_status`: Trạng thái thanh toán (`pending`: Chờ thanh toán, `paid`: Đã thanh toán, `failed`: Lỗi, `refunded`: Hoàn tiền).
- `payment_method`: Hình thức thanh toán (COD, Momo, VNPay...).
- `shipping_address_id`: Khóa ngoại tham chiếu đến địa chỉ giao.
- `total_amount`: Tổng giá trị thanh toán của đơn.
- `notes`: Ghi chú từ người mua.

### 1.6. Bảng `order_items` (Chi tiết đơn hàng)
Lưu trữ chi tiết các mặt hàng trong một đơn hàng cụ thể.
- `id`: Khóa chính.
- `order_id`: Khóa ngoại liên kết với bảng `orders`.
- `product_id`: Khóa ngoại liên kết với `products`.
- `quantity`: Số lượng mua của mặt hàng này.
- `price`: Đơn giá tại **thời điểm mua** (lưu lại cứng để không bị đổi nếu sau này cập nhật giá sản phẩm).
- `subtotal`: Thành tiền (`quantity * price`).

### 1.7. Bảng `banners`, `home_sections`, `settings`
- `banners`: Quản lý các ảnh slider quảng cáo, bao gồm tiêu đề, link chuyển hướng, thứ tự (`order`), và trạng thái `is_active`.
- `home_sections`: Quản lý linh hoạt cấu trúc trang chủ (hiển thị block "Sản phẩm mới", "Khuyến mãi"...).
- `settings`: Lưu cấu hình động (key-value) cho website như Tên web, Logo, Hotline, Địa chỉ công ty.

---

## 2. Phân tích và Đặc tả chức năng chi tiết

Hệ thống được chia thành 2 nhóm tác nhân (Actors) chính với các luồng nghiệp vụ riêng biệt: Khách hàng (Client) và Quản trị viên (Admin).

### 2.1. Tác nhân Khách hàng (Client/User)

**2.1.1. Đăng ký & Đăng nhập (Auth)**
- **Mục đích:** Cho phép khách truy cập trở thành thành viên để được mua hàng, lưu địa chỉ và quản lý lịch sử đơn hàng.
- **Luồng nghiệp vụ:**
  - Khách hàng điền thông tin (Họ tên, Email, Mật khẩu). Hệ thống validate độ mạnh của mật khẩu và tính duy nhất của Email.
  - Hệ thống băm (hash) mật khẩu và lưu vào CSDL.
  - Khi đăng nhập, hệ thống xác thực (Auth::attempt). Hỗ trợ tính năng "Remember Me" qua cookie dài hạn.
- **Ngoại lệ:** Cố ý nhập sai mật khẩu nhiều lần sẽ bị khóa đăng nhập tạm thời (rate limiting).

**2.1.2. Quản lý Hồ sơ và Địa chỉ giao hàng**
- **Sổ địa chỉ:** Hệ thống giải quyết bài toán khách hàng mua hàng cho mình hoặc mua gửi tặng người khác. Do đó, một người dùng có thể tạo **nhiều địa chỉ**.
- **Luồng nghiệp vụ:**
  - Khách hàng vào trang Sổ địa chỉ -> Nhấn "Thêm địa chỉ mới".
  - Điền Tên người nhận, SĐT, chọn Tỉnh/Thành, Quận/Huyện, Phường/Xã (liên kết với API hành chính nếu có) và địa chỉ nhà.
  - Cho phép người dùng tick chọn "Làm địa chỉ mặc định". Khi thiết lập, các địa chỉ khác của người này sẽ tự động chuyển `is_default = false`.

**2.1.3. Duyệt, Tìm kiếm, và Lọc sản phẩm**
- **Mục đích:** Trải nghiệm tìm kiếm dễ dàng là cốt lõi của TMĐT.
- **Luồng nghiệp vụ:**
  - **Duyệt danh mục:** Thanh menu hiển thị cây danh mục. Click vào "Laptop", truy vấn lấy các sản phẩm có `category_id` tương ứng, chỉ lấy `is_active = true`.
  - **Tìm kiếm từ khóa:** Ô search hỗ trợ tìm theo `name` hoặc `description` (sử dụng query `LIKE` hoặc Fulltext search).
  - **Bộ lọc & Sắp xếp:** Trộn các tiêu chí (Ví dụ: Danh mục = Điện thoại + Giá từ 5 - 10 triệu + Sắp xếp: Mới nhất).
- **Chi tiết sản phẩm:** Giao diện hiển thị đầy đủ Ảnh, Giá, Tình trạng tồn kho, Nút "Thêm vào giỏ", Mô tả dài và Khối "Sản phẩm cùng danh mục" (Related products).

**2.1.4. Quản lý Giỏ hàng (Cart)**
- **Mục đích:** Nơi tập kết sản phẩm trước khi chốt đơn.
- **Luồng nghiệp vụ:**
  - Thêm sản phẩm: Hệ thống nhận tham số `product_id` và `quantity`. Nếu `stock` (tồn kho) < `quantity` yêu cầu, hiển thị lỗi báo hết hàng.
  - Nếu sản phẩm đã có trong giỏ, tự động cộng dồn số lượng.
  - Khách hàng có thể thay đổi số lượng (+/-) ngay tại trang Giỏ hàng. Tổng tiền (Subtotal) sẽ được tính lại ngay lập tức (sử dụng Ajax cập nhật realtime).

**2.1.5. Đặt hàng và Thanh toán (Checkout)**
- **Điều kiện tiên quyết:** Phải có sản phẩm trong giỏ và khách phải ở trạng thái đã đăng nhập.
- **Luồng nghiệp vụ:**
  1. Vào trang Checkout, hệ thống tự load Sổ địa chỉ của người dùng. Nếu chưa có, yêu cầu nhập mới.
  2. Hiển thị tổng tiền cần thanh toán.
  3. Khách hàng chọn phương thức thanh toán (COD - nhận hàng trả tiền hoặc Online payment).
  4. Xác nhận đặt hàng.
  5. **Transaction xử lý phía Backend:**
     - Tạo 1 bản ghi mới trong bảng `orders`.
     - Lặp qua các món trong giỏ hàng, tạo các bản ghi `order_items`. Chú ý: Cột `price` trong `order_items` phải được gán cứng bằng giá sản phẩm ở thời điểm hiện tại.
     - **Trừ tồn kho:** Cập nhật bảng `products` (Giảm `stock` đi số lượng tương ứng).
     - Xóa trống giỏ hàng của user.
  6. Hệ thống có thể bắn email thông báo đặt hàng thành công.

**2.1.6. Quản lý Lịch sử Đơn hàng**
- **Luồng nghiệp vụ:**
  - Danh sách đơn hàng được sắp xếp theo thời gian đặt (mới nhất lên đầu).
  - Trạng thái rõ ràng bằng màu sắc: Chờ xử lý (Vàng), Đang giao (Xanh dương), Hoàn thành (Xanh lá), Hủy (Đỏ).
  - Khách hàng có thể bấm "Hủy đơn" nếu đơn hàng vẫn đang ở trạng thái `pending`. Nếu Admin đã chuyển sang `processing` trở đi, nút Hủy sẽ bị ẩn.

---

### 2.2. Tác nhân Quản trị viên (Admin)

**2.2.1. Tổng quan bảng điều khiển (Dashboard)**
- **Mục đích:** Công cụ giúp chủ cửa hàng ra quyết định kinh doanh.
- **Hiển thị:** 
  - Khối thống kê KPI: Doanh thu tháng, Số đơn hoàn thành, Số khách mới đăng ký.
  - Cảnh báo: Danh sách các sản phẩm sắp hết hàng (Stock <= mức quy định).
  - Biểu đồ: Sử dụng Chart.js vẽ biểu đồ cột cho Doanh thu theo ngày trong tháng, và Pie chart thể hiện tỉ lệ trạng thái đơn hàng.

**2.2.2. Quản lý Danh mục (Categories)**
- **Luồng nghiệp vụ:**
  - CRUD danh mục: Thêm, Sửa, Xóa.
  - Thiết lập phân cấp cha con qua trường `parent_id`.
  - Nếu Admin đổi trạng thái `is_active` của một danh mục cha thành `false`, toàn bộ danh mục con và sản phẩm bên trong sẽ không được hiển thị ra ngoài Frontend.

**2.2.3. Quản lý Sản phẩm (Products)**
- **Luồng nghiệp vụ:**
  - Khởi tạo sản phẩm: Nhập tên, hệ thống tự động sinh `slug` (ví dụ: "iPhone 15" -> `iphone-15`).
  - Thiết lập giá (`price`) và số lượng trong kho (`stock`).
  - Quản lý nội dung mô tả bằng trình soạn thảo văn bản phong phú (Rich Text Editor như CKEditor).
  - Đánh dấu `is_featured` để ghim sản phẩm đó ra các vị trí đẹp trên trang chủ.

**2.2.4. Quản lý Đơn hàng (Order Processing Workflow)**
- **Mục đích:** Xử lý luồng vận chuyển và thanh toán.
- **Luồng nghiệp vụ xử lý trạng thái (`status`):**
  1. Đơn mới vào sẽ có trạng thái `pending`.
  2. Admin gọi điện xác nhận, chuyển sang `processing` (Đóng gói).
  3. Giao cho shipper/bưu điện, chuyển sang `shipping`.
  4. Khách nhận thành công, chuyển sang `completed`.
  5. Nếu khách boom hàng hoặc hết hàng, chuyển sang `cancelled` **(Đồng thời hệ thống cần chạy logic cộng ngược lại `stock` cho sản phẩm trong kho).**
- **Trạng thái thanh toán (`payment_status`):** Quản lý độc lập. Ví dụ đơn COD thì lúc giao xong mới chuyển `payment_status` thành `paid`.

**2.2.5. Quản lý Nội dung trang chủ (Banners & Sections) và Cấu hình**
- Quản lý các block hiển thị động (VD: Khối sản phẩm bán chạy nhất, Khối sản phẩm khuyến mãi) thông qua bảng `home_sections`.
- Đổi logo, số điện thoại chăm sóc khách hàng, liên kết Facebook/Tiktok trực tiếp từ trang quản trị thông qua cấu hình trong bảng `settings` mà không cần can thiệp vào Source code.

---

## 3. Thiết kế & Cài đặt Kiến trúc (Architecture Implementation)

### 3.1. Mô hình Kiến trúc MVC
- Hệ thống áp dụng triệt để mô hình **MVC của Laravel**.
- **Model:** Mỗi bảng DB đều có 1 Model kế thừa từ `Illuminate\Database\Eloquent\Model`. Định nghĩa rõ ràng các Relationships:
  - `User hasMany Address`, `User hasMany Order`.
  - `Category hasMany Product`, `Category hasMany Category` (Trỏ đệ quy lấy danh mục con).
  - `Order hasMany OrderItem`, `OrderItem belongsTo Product`.
- **View:** Sử dụng Blade Template Engine. Tách biệt Layout chung (`app.blade.php`), các thành phần lặp lại (Header, Footer, Sidebar, Product Card) thành các `components` hoặc `partials` để tối ưu tái sử dụng.
- **Controller:** Tách biệt `AdminController` và `ClientController` để xử lý logic không bị chồng chéo. Sử dụng Form Request Validation để kiểm tra dữ liệu đầu vào.

### 3.2. Bảo mật & Phân quyền (Security & Authorization)
- **Middleware:**
  - `auth`: Bắt buộc người dùng đăng nhập để vào Giỏ hàng, Thanh toán, Hồ sơ.
  - `admin`: Một custom middleware kiểm tra `$user->is_admin == true`. Nếu `false`, trả về lỗi 403 Forbidden hoặc redirect về trang chủ. Bảo vệ toàn bộ prefix Route `/admin/*`.
- **Bảo mật Form:** Mọi form POST/PUT/DELETE đều yêu cầu `@csrf` token chống tấn công giả mạo (Cross-Site Request Forgery).
- **SQL Injection:** Mọi truy vấn đều sử dụng Eloquent ORM và Query Builder, tham số truyền vào tự động được bind bằng PDO an toàn tuyệt đối.

### 3.3. Xử lý Tối ưu hiệu năng (Performance Optimization)
- **Vấn đề N+1 Query:**
  - Trong Laravel, khi gọi 100 Sản phẩm ra trang chủ, nếu mỗi vòng lặp gọi `{{ $product->category->name }}` thì hệ thống sẽ thực hiện thêm 100 câu query tới bảng categories.
  - **Khắc phục:** Sử dụng Eager Loading trong Controller: `Product::with('category')->get();` (Chỉ tốn đúng 2 câu query).
- **Transaction cho Thanh toán:**
  - Như mô tả ở phần 2.1.5, toàn bộ thao tác thêm Order, trừ Kho, trừ Giỏ hàng được bọc trong hàm `DB::transaction(function() { ... });`. Nếu bất kỳ thao tác nào lỗi, hệ thống tự động `rollback` trạng thái cũ, đảm bảo tính toàn vẹn dữ liệu.

### 3.4. Quản lý Đa ngôn ngữ (Localization)
- Trang web hỗ trợ đa ngôn ngữ bằng gói Localization của Laravel.
- Các chuỗi văn bản tĩnh được khai báo trong thư mục `resources/lang/en/` và `resources/lang/vi/`.
- Khi người dùng bấm đổi ngôn ngữ (icon lá cờ), hệ thống gửi request lên Controller lưu key ngôn ngữ (vi/en) vào Session (chạy bằng middleware để set `App::setLocale($locale)`). View sẽ render văn bản bằng hàm `__('messages.home')`.

Tài liệu này đóng vai trò như bản thiết kế khung cấp tiến, vừa đặc tả rõ chi tiết từng Use-case, vừa làm bản quy chuẩn kỹ thuật cho đội ngũ triển khai phát triển dự án Eco Web.
