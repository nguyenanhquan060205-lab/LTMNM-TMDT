//using System;
//using System.Collections.Generic;
//using System.Data.Entity;
//using System.IO;
//using System.Linq;
//using System.Web;
//using System.Web.Mvc;
//using ThuongMaiDienTu_DoAn.Models;

//namespace ThuongMaiDienTu_DoAn.Controllers
//{
//    public class TaiKhoanController : Controller
//    {
//        private readonly TMDTEntities db = new TMDTEntities();

//        [HttpGet]
//        public ActionResult DangNhap()
//        {
//            return View();
//        }

//        [HttpPost]
//        public ActionResult DangNhap(string taikhoan, string matkhau)
//        {
//            if (string.IsNullOrWhiteSpace(taikhoan) || string.IsNullOrWhiteSpace(matkhau))
//            {
//                ViewBag.Error = "Vui lòng nhập đầy đủ thông tin đăng nhập!";
//                return View();
//            }

//            var user = db.NGUOIDUNGs
//                .FirstOrDefault(u => u.TaiKhoan == taikhoan && u.MatKhau == matkhau);

//            if (user == null)
//            {
//                ViewBag.Error = "Sai tài khoản hoặc mật khẩu!";
//                return View();
//            }

//            Session["user"] = user;
//            if (user.VaiTro == "Admin")
//                return RedirectToAction("Index", "Admin");
//            else
//                return RedirectToAction("Index", "Home");
//        }


//        [HttpGet]
//        public ActionResult DangKy()
//        {
//            return View();
//        }

//        [HttpPost]
//        public ActionResult DangKy(NGUOIDUNG nd)
//        {
//            if (!ModelState.IsValid)
//                return View(nd);

//            if (db.NGUOIDUNGs.Any(x => x.TaiKhoan == nd.TaiKhoan || x.Email == nd.Email))
//            {
//                ViewBag.Error = "Tài khoản hoặc email đã tồn tại!";
//                return View(nd);
//            }

//            nd.VaiTro = "User";
//            nd.NgayTao = DateTime.Now;
//            nd.AnhDaiDien = "default.jpg";

//            db.NGUOIDUNGs.Add(nd);
//            db.SaveChanges();

//            //Session["user"] = nd;
//            return RedirectToAction("DangNhap", "TaiKhoan");
//        }


//        // GET: TaiKhoan/ThongTinKhachHang/5
//        [HttpGet]
//        public ActionResult ThongTinKhachHang(int? id)
//        {
//            var currentUser = Session["user"] as NGUOIDUNG;

//            // 1. Chưa đăng nhập -> Đá về Login
//            if (currentUser == null)
//            {
//                return RedirectToAction("DangNhap", "TaiKhoan");
//            }

//            NGUOIDUNG targetUser;

//            // 2. LOGIC QUAN TRỌNG:
//            // Nếu có ID truyền vào VÀ người đang đăng nhập là Admin -> Xem thông tin người khác
//            if (id.HasValue && currentUser.VaiTro == "Admin")
//            {
//                targetUser = db.NGUOIDUNGs.Find(id);
//                if (targetUser == null) return HttpNotFound(); // Không tìm thấy user này
//            }
//            else
//            {
//                // Ngược lại (Khách xem mình hoặc Admin xem mình) -> Lấy từ Session
//                targetUser = db.NGUOIDUNGs.Find(currentUser.MaKH);
//            }

//            return View(targetUser);
//        }

//        // GET: TaiKhoan/ThongTinAdmin
//        public ActionResult ThongTinAdmin()
//        {
//            var user = Session["user"] as ThuongMaiDienTu_DoAn.Models.NGUOIDUNG;

//            // Kiểm tra: Nếu chưa đăng nhập hoặc không phải Admin thì đuổi về trang đăng nhập
//            if (user == null || user.VaiTro != "Admin")
//            {
//                return RedirectToAction("DangNhap", "TaiKhoan");
//            }

//            return View(user);
//        }


//        [HttpPost]
//        public ActionResult CapNhatThongTin(NGUOIDUNG model, HttpPostedFileBase fileUpload)
//        {
//            // 1. Tìm user trong DB trước để lấy VaiTro
//            var user = db.NGUOIDUNGs.Find(model.MaKH);
//            if (user == null) return RedirectToAction("DangNhap");

//            // 2. XÁC ĐỊNH TRANG ĐÍCH (QUAN TRỌNG)
//            // Nếu là Admin -> Về ThongTinAdmin
//            // Nếu là Khách -> Về ThongTinKhachHang
//            string actionName = (user.VaiTro == "Admin") ? "ThongTinAdmin" : "ThongTinKhachHang";

//            try
//            {
//                // Kiểm tra Email trùng
//                if (!string.IsNullOrWhiteSpace(model.Email) &&
//                    db.NGUOIDUNGs.Any(x => x.Email == model.Email && x.MaKH != model.MaKH))
//                {
//                    TempData["Error"] = "Email đã được sử dụng bởi tài khoản khác!";
//                    return RedirectToAction(actionName); // Trả về đúng trang
//                }

//                // Kiểm tra SĐT trùng
//                if (!string.IsNullOrWhiteSpace(model.SDT) &&
//                    db.NGUOIDUNGs.Any(x => x.SDT == model.SDT && x.MaKH != model.MaKH))
//                {
//                    TempData["Error"] = "Số điện thoại đã được sử dụng bởi tài khoản khác!";
//                    return RedirectToAction(actionName); // Trả về đúng trang
//                }

//                // Upload ảnh đại diện
//                if (fileUpload != null && fileUpload.ContentLength > 0)
//                {
//                    string[] allowedExt = { ".jpg", ".jpeg", ".png", ".gif", ".webp" };
//                    string ext = Path.GetExtension(fileUpload.FileName).ToLower();

//                    if (!allowedExt.Contains(ext))
//                    {
//                        TempData["Error"] = "Định dạng ảnh không hợp lệ!";
//                        return RedirectToAction(actionName); // Trả về đúng trang
//                    }

//                    // Tạo thư mục
//                    string folder = Server.MapPath("~/Content/Avatars");
//                    if (!Directory.Exists(folder)) Directory.CreateDirectory(folder);

//                    // Tạo tên file
//                    string fileName = $"user_{user.MaKH}_{DateTime.Now.Ticks}{ext}";
//                    string path = Path.Combine(folder, fileName);

//                    // Lưu file
//                    fileUpload.SaveAs(path);

//                    // Xóa ảnh cũ
//                    if (!string.IsNullOrEmpty(user.AnhDaiDien) && user.AnhDaiDien != "default.jpg")
//                    {
//                        string oldPath = Path.Combine(folder, user.AnhDaiDien);
//                        if (System.IO.File.Exists(oldPath)) System.IO.File.Delete(oldPath);
//                    }

//                    user.AnhDaiDien = fileName;
//                }

//                // Cập nhật thông tin
//                user.HoTen = model.HoTen;
//                user.GioiTinh = model.GioiTinh;
//                // user.Email = model.Email; // Thường Email là tên đăng nhập, hạn chế cho sửa, nhưng nếu bạn muốn sửa thì bỏ comment
//                user.SDT = model.SDT;
//                user.DiaChi = model.DiaChi;

//                db.Entry(user).State = EntityState.Modified;
//                db.SaveChanges();

//                // Cập nhật lại Session để hiển thị ngay lập tức trên Header
//                Session["user"] = user;

//                TempData["Success"] = "✅ Cập nhật thông tin thành công!";
//            }
//            catch (Exception ex)
//            {
//                // Ghi log lỗi (Optional)
//                TempData["Error"] = "Đã xảy ra lỗi hệ thống: " + ex.Message;
//            }

//            // Quay về đúng trang đích đã xác định ở trên
//            return RedirectToAction(actionName);
//        }

//        // ========== [ĐĂNG XUẤT] ==========
//        public ActionResult DangXuat()
//        {
//            Session.Clear();
//            return RedirectToAction("Index", "Home");
//        }

//        // ========== [Lịch sử] ==========
//        public ActionResult LichSu()
//        {
//            var kh = Session["user"] as NGUOIDUNG;
//            if (kh == null)
//                return RedirectToAction("DangNhap", "TaiKhoan");

//            var dsDonHang = db.HOADONs
//                .Where(d => d.MaKH == kh.MaKH)
//                .OrderByDescending(d => d.NgayDat)
//                .Select(d => new LichSuViewModel
//                {
//                    MaHD = d.MaHD,
//                    NgayDat = d.NgayDat,
//                    NgayTT = d.NgayTT,
//                    TrangThai = d.TrangThai,
//                    PhuongThucTT = d.PhuongThucTT,
//                    DaDanhGia = db.CT_HOADON
//                                    .Where(ct => ct.MaHD == d.MaHD)
//                                    .All(ct => db.DANHGIAs.Any(dg => dg.MaKH == kh.MaKH && dg.MaSP == ct.MaSP))
//                })
//                .ToList();

//            return View(dsDonHang);
//        }

//        public ActionResult CT_LichSu(int id)
//        {
//            var kh = Session["user"] as NGUOIDUNG;

//            // 1. Bắt đăng nhập
//            if (kh == null)
//                return RedirectToAction("DangNhap", "TaiKhoan");

//            // 2. Lấy hóa đơn
//            var hd = db.HOADONs.FirstOrDefault(h => h.MaHD == id);
//            if (hd == null)
//                return HttpNotFound();

//            // 3. Check quyền: chỉ chủ đơn hoặc Admin mới xem được
//            if (hd.MaKH != kh.MaKH && kh.VaiTro != "Admin")
//                return new HttpStatusCodeResult(403, "Bạn không có quyền xem đơn hàng này.");

//            // 4. Map CT_HOADON -> ViewModel đúng kiểu mà view cần
//            var chiTietVm = db.CT_HOADON
//                .Where(ct => ct.MaHD == id)
//                .Select(ct => new ChiTietHoaDonViewModel
//                {
//                    MaHD = ct.MaHD,
//                    MaSP = ct.MaSP,
//                    TenSP = ct.SANPHAM.TenSP,
//                    SoLuong = ct.SoLuong,
//                    ThanhTien = ct.ThanhTien,
//                    TrangThaiCT = ct.TrangThaiCT,
//                    DaDanhGia = db.DANHGIAs.Any(d => d.MaHD == ct.MaHD && d.MaSP == ct.MaSP),
//                    DaKhieuNai = db.KHIEUNAIs.Any(k => k.MaSP == ct.MaHD && k.MaSP == ct.MaSP)
//                })
//                .ToList();

//            // 5. Đưa HOADON sang view bằng ViewBag (view của bạn đang dùng ViewBag.HoaDon)
//            ViewBag.HoaDon = hd;

//            // 6. Trả đúng kiểu model mà view khai báo
//            return View(chiTietVm);
//        }

//        [HttpGet]
//        public ActionResult HuyDonHang(int id)
//        {
//            var kh = Session["user"] as NGUOIDUNG;
//            if (kh == null)
//            {
//                TempData["ThongBao"] = "Vui lòng đăng nhập để thực hiện!";
//                return RedirectToAction("DangNhap", "TaiKhoan");
//            }

//            var hd = db.HOADONs.FirstOrDefault(d => d.MaHD == id && d.MaKH == kh.MaKH);
//            if (hd == null)
//            {
//                return HttpNotFound();
//            }

//            if (hd.TrangThai == "Đang chờ xử lý")
//            {
//                var chiTiet = db.CT_HOADON.Where(ct => ct.MaHD == hd.MaHD).ToList();

//                foreach (var item in chiTiet)
//                {
//                    // Trả lại kho
//                    var sp = db.SANPHAMs.Find(item.MaSP);
//                    if (sp != null)
//                    {
//                        sp.SoLuong += item.SoLuong;

//                        if (sp.TrangThai == "Đã bán" && sp.SoLuong > 0)
//                            sp.TrangThai = "Đã duyệt";
//                    }

//                    // 🔥 Update trạng thái chi tiết
//                    item.TrangThaiCT = "Đã Huỷ".Normalize();
//                    // <-- chữ H và chữ Y CHUẨN với DB
//                }

//                // 🔥 Update trạng thái hóa đơn
//                hd.TrangThai = "Đã Huỷ";

//                db.SaveChanges();

//                TempData["ThongBao"] = "Đơn hàng đã được hủy thành công!";
//            }
//            else
//            {
//                TempData["ThongBao"] = "Đơn hàng không thể hủy vì đã giao hoặc hoàn tất!";
//            }

//            return RedirectToAction("LichSu");
//        }


//        [HttpGet]
//        public ActionResult SuaDonHang(int id)
//        {
//            var kh = Session["user"] as NGUOIDUNG;
//            if (kh == null)
//            {
//                return RedirectToAction("DangNhap", "TaiKhoan");
//            }

//            var hd = db.HOADONs.FirstOrDefault(d => d.MaHD == id && d.MaKH == kh.MaKH);
//            if (hd == null)
//            {
//                return HttpNotFound();
//            }

//            return View(hd);
//        }

//        [HttpPost]
//        [ValidateAntiForgeryToken]
//        public ActionResult SuaDonHang(HOADON model)
//        {
//            var kh = Session["user"] as NGUOIDUNG;
//            if (kh == null)
//                return RedirectToAction("DangNhap", "TaiKhoan");

//            var hd = db.HOADONs.Include("CT_HOADON")
//                       .FirstOrDefault(d => d.MaHD == model.MaHD && d.MaKH == kh.MaKH);
//            if (hd == null)
//                return HttpNotFound();

//            // Cập nhật số lượng từ model (nếu cần)
//            foreach (var ctModel in model.CT_HOADON)
//            {
//                var ct = hd.CT_HOADON.FirstOrDefault(c => c.MaSP == ctModel.MaSP);
//                if (ct != null)
//                {
//                    var sp = db.SANPHAMs.Find(ct.MaSP);
//                    if (sp == null)
//                    {
//                        ModelState.AddModelError("", $"Sản phẩm {ct.MaSP} không tồn tại!");
//                        return View(hd);
//                    }

//                    // Kiểm tra tồn kho
//                    if (ctModel.SoLuong > sp.SoLuong + ct.SoLuong)
//                    {
//                        ModelState.AddModelError("",
//                            $"Số lượng sản phẩm '{sp.TenSP}' không đủ. Tồn kho: {sp.SoLuong + ct.SoLuong}");
//                        return View(hd);
//                    }

//                    ct.SoLuong = ctModel.SoLuong;
//                    ct.ThanhTien = (decimal)(ct.SoLuong * sp.Gia);
//                }
//            }

//            // Cập nhật thông tin HOADON
//            hd.PhuongThucTT = model.PhuongThucTT;
//            hd.DiaChiGiaoHang = model.DiaChiGiaoHang;

//            db.SaveChanges();
//            TempData["ThongBao"] = "Cập nhật đơn hàng thành công!";
//            return RedirectToAction("LichSu");
//        }
//        // GET: Hiển thị form đánh giá
//        public ActionResult DanhGia(int maHD, int maSP)
//        {
//            var ct = db.CT_HOADON
//                       .Where(x => x.MaHD == maHD && x.MaSP == maSP)
//                       .Select(x => new DanhGiaViewModel
//                       {
//                           MaHD = x.MaHD,
//                           MaSP = x.MaSP,
//                           TenSP = x.SANPHAM.TenSP,
//                           Hinh = db.HINHANHSPs
//                                    .Where(h => h.Masp == x.MaSP && h.AnhBia == true)
//                                    .Select(h => h.URLAnh)
//                                    .FirstOrDefault(),
//                           DaDanhGia = x.DaDanhGia
//                       })
//                       .FirstOrDefault();

//            if (ct == null)
//                return HttpNotFound();

//            return View(ct);
//        }

//        // POST: Lưu đánh giá
//        [HttpPost]
//        [ValidateAntiForgeryToken]
//        public ActionResult DanhGia(int maHD, int maSP, int soSao, string noiDung)
//        {
//            var kh = Session["user"] as NGUOIDUNG;
//            if (kh == null)
//                return RedirectToAction("DangNhap", "TaiKhoan");

//            var danhGia = new DANHGIA
//            {
//                MaKH = kh.MaKH,
//                MaSP = maSP,
//                MaHD = maHD,
//                SoSao = soSao,
//                NoiDung = noiDung,
//                NgayDG = DateTime.Now
//            };
//            db.DANHGIAs.Add(danhGia);
//            db.SaveChanges();

//            TempData["ThongBao"] = "✅ Cảm ơn bạn đã đánh giá sản phẩm!";
//            return RedirectToAction("LichSu");
//        }

//    }
//}

using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.IO;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using ThuongMaiDienTu_DoAn.Models;

namespace ThuongMaiDienTu_DoAn.Controllers
{
    public class TaiKhoanController : Controller
    {
        private readonly TMDTEntities db = new TMDTEntities();

        [HttpGet]
        public ActionResult DangNhap()
        {
            return View();
        }

        [HttpPost]
        public ActionResult DangNhap(string taikhoan, string matkhau)
        {
            if (string.IsNullOrWhiteSpace(taikhoan) || string.IsNullOrWhiteSpace(matkhau))
            {
                ViewBag.Error = "Vui lòng nhập đầy đủ thông tin đăng nhập!";
                return View();
            }

            var user = db.NGUOIDUNGs
                .FirstOrDefault(u => u.TaiKhoan == taikhoan && u.MatKhau == matkhau);

            if (user == null)
            {
                ViewBag.Error = "Sai tài khoản hoặc mật khẩu!";
                return View();
            }

            //CHẶN TÀI KHOẢN BỊ KHÓA
            if (user.Khoa == true)
            {
                ViewBag.Error = "Tài khoản của bạn đã bị khóa! Vui lòng liên hệ Admin.";
                return View();
            }

            // ĐĂNG NHẬP
            Session["user"] = user;

            if (user.VaiTro == "Admin")
                return RedirectToAction("Index", "Admin");
            else
                return RedirectToAction("Index", "Home");
        }



        [HttpGet]
        public ActionResult DangKy()
        {
            return View();
        }

        [HttpPost]
        public ActionResult DangKy(NGUOIDUNG nd)
        {
            if (!ModelState.IsValid)
                return View(nd);

            if (db.NGUOIDUNGs.Any(x => x.TaiKhoan == nd.TaiKhoan || x.Email == nd.Email || x.SDT == nd.SDT))
            {
                ViewBag.Error = "Tài khoản, email hoặc số điện thoại đã tồn tại!";
                return View(nd);
            }

            nd.VaiTro = "User";
            nd.NgayTao = DateTime.Now;
            nd.AnhDaiDien = "default.jpg";

            db.NGUOIDUNGs.Add(nd);
            db.SaveChanges();

            return RedirectToAction("DangNhap");
        }


        // GET: TaiKhoan/ThongTinKhachHang/5
        [HttpGet]
        public ActionResult ThongTinKhachHang(int? id)
        {
            var currentUser = Session["user"] as NGUOIDUNG;

            // 1. Chưa đăng nhập -> Đá về Login
            if (currentUser == null)
            {
                return RedirectToAction("DangNhap", "TaiKhoan");
            }

            NGUOIDUNG targetUser;

            // 2. LOGIC QUAN TRỌNG:
            // Nếu có ID truyền vào VÀ người đang đăng nhập là Admin -> Xem thông tin người khác
            if (id.HasValue && currentUser.VaiTro == "Admin")
            {
                targetUser = db.NGUOIDUNGs.Find(id);
                if (targetUser == null) return HttpNotFound(); // Không tìm thấy user này
            }
            else
            {
                // Ngược lại (Khách xem mình hoặc Admin xem mình) -> Lấy từ Session
                targetUser = db.NGUOIDUNGs.Find(currentUser.MaKH);
            }

            return View(targetUser);
        }

        // GET: TaiKhoan/ThongTinAdmin
        public ActionResult ThongTinAdmin()
        {
            var user = Session["user"] as ThuongMaiDienTu_DoAn.Models.NGUOIDUNG;

            // Kiểm tra: Nếu chưa đăng nhập hoặc không phải Admin thì đuổi về trang đăng nhập
            if (user == null || user.VaiTro != "Admin")
            {
                return RedirectToAction("DangNhap", "TaiKhoan");
            }

            return View(user);
        }


        [HttpPost]
        public ActionResult CapNhatThongTin(NGUOIDUNG model, HttpPostedFileBase fileUpload)
        {
            // 1. Tìm user trong DB trước để lấy VaiTro
            var user = db.NGUOIDUNGs.Find(model.MaKH);
            if (user == null) return RedirectToAction("DangNhap");

            // 2. XÁC ĐỊNH TRANG ĐÍCH (QUAN TRỌNG)
            // Nếu là Admin -> Về ThongTinAdmin
            // Nếu là Khách -> Về ThongTinKhachHang
            string actionName = (user.VaiTro == "Admin") ? "ThongTinAdmin" : "ThongTinKhachHang";

            try
            {
                // Kiểm tra Email trùng
                if (!string.IsNullOrWhiteSpace(model.Email) &&
                    db.NGUOIDUNGs.Any(x => x.Email == model.Email && x.MaKH != model.MaKH))
                {
                    TempData["Error"] = "Email đã được sử dụng bởi tài khoản khác!";
                    return RedirectToAction(actionName); // Trả về đúng trang
                }

                // Kiểm tra SĐT trùng
                if (!string.IsNullOrWhiteSpace(model.SDT) &&
                    db.NGUOIDUNGs.Any(x => x.SDT == model.SDT && x.MaKH != model.MaKH))
                {
                    TempData["Error"] = "Số điện thoại đã được sử dụng bởi tài khoản khác!";
                    return RedirectToAction(actionName); // Trả về đúng trang
                }

                // Upload ảnh đại diện
                if (fileUpload != null && fileUpload.ContentLength > 0)
                {
                    string[] allowedExt = { ".jpg", ".jpeg", ".png", ".gif", ".webp" };
                    string ext = Path.GetExtension(fileUpload.FileName).ToLower();

                    if (!allowedExt.Contains(ext))
                    {
                        TempData["Error"] = "Định dạng ảnh không hợp lệ!";
                        return RedirectToAction(actionName); // Trả về đúng trang
                    }

                    // Tạo thư mục
                    string folder = Server.MapPath("~/Content/Avatars");
                    if (!Directory.Exists(folder)) Directory.CreateDirectory(folder);

                    // Tạo tên file
                    string fileName = $"user_{user.MaKH}_{DateTime.Now.Ticks}{ext}";
                    string path = Path.Combine(folder, fileName);

                    // Lưu file
                    fileUpload.SaveAs(path);

                    // Xóa ảnh cũ
                    if (!string.IsNullOrEmpty(user.AnhDaiDien) && user.AnhDaiDien != "default.jpg")
                    {
                        string oldPath = Path.Combine(folder, user.AnhDaiDien);
                        if (System.IO.File.Exists(oldPath)) System.IO.File.Delete(oldPath);
                    }

                    user.AnhDaiDien = fileName;
                }

                // Cập nhật thông tin
                user.HoTen = model.HoTen;
                user.GioiTinh = model.GioiTinh;
                // user.Email = model.Email; // Thường Email là tên đăng nhập, hạn chế cho sửa, nhưng nếu bạn muốn sửa thì bỏ comment
                user.SDT = model.SDT;
                user.DiaChi = model.DiaChi;

                db.Entry(user).State = EntityState.Modified;
                db.SaveChanges();

                // Cập nhật lại Session để hiển thị ngay lập tức trên Header
                Session["user"] = user;

                TempData["Success"] = "✅ Cập nhật thông tin thành công!";
            }
            catch (Exception ex)
            {
                // Ghi log lỗi (Optional)
                TempData["Error"] = "Đã xảy ra lỗi hệ thống: " + ex.Message;
            }

            // Quay về đúng trang đích đã xác định ở trên
            return RedirectToAction(actionName);
        }

        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult CapNhatChuyenKhoan(int MaKH, string SoTaiKhoan, string TenNganHang)
        {
            var user = db.NGUOIDUNGs.Find(MaKH);
            if (user == null) return HttpNotFound();

            user.SoTaiKhoan = SoTaiKhoan;
            user.TenNganHang = TenNganHang;

            db.SaveChanges();
            TempData["Success"] = "Cập nhật thông tin chuyển khoản thành công!";
            return RedirectToAction("ThongTinKhachHang");
        }

        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult CapNhatMatKhau(int MaKH, string MatKhauHienTai, string MatKhauMoi, string XacNhanMatKhauMoi)
        {
            var user = db.NGUOIDUNGs.Find(MaKH);
            if (user == null) return HttpNotFound();

            if (user.MatKhau != MatKhauHienTai)
            {
                TempData["Error"] = "Mật khẩu hiện tại không đúng!";
                return RedirectToAction("ThongTinKhachHang");
            }

            if (MatKhauMoi != XacNhanMatKhauMoi)
            {
                TempData["Error"] = "Xác nhận mật khẩu không khớp!";
                return RedirectToAction("ThongTinKhachHang");
            }

            user.MatKhau = MatKhauMoi; 
            db.SaveChanges();
            TempData["Success"] = "Cập nhật mật khẩu thành công!";
            return RedirectToAction("ThongTinKhachHang");
        }

        public ActionResult ThongTinChuyenKhoan(int idNguoiBan)
        {
            var nguoiBan = db.NGUOIDUNGs.Find(idNguoiBan);

            var thongTinCK = new
            {
                HoTen = nguoiBan.HoTen,
                SoTaiKhoan = nguoiBan.SoTaiKhoan,
                TenNganHang = nguoiBan.TenNganHang
            };

            return Json(thongTinCK, JsonRequestBehavior.AllowGet);
        }

        // ========== [ĐĂNG XUẤT] ==========
        public ActionResult DangXuat()
        {
            Session.Clear();
            return RedirectToAction("Index", "Home");
        }

        // ========== [Lịch sử] ==========
        public ActionResult LichSu()
        {
            var kh = Session["user"] as NGUOIDUNG;
            if (kh == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            var dsDonHang = db.HOADONs
                .Where(d => d.MaKH == kh.MaKH)
                .OrderByDescending(d => d.NgayDat)
                .Select(d => new LichSuViewModel
                {
                    MaHD = d.MaHD,
                    NgayDat = d.NgayDat,
                    NgayTT = d.NgayTT,
                    TrangThai = d.TrangThai,
                    PhuongThucTT = d.PhuongThucTT,
                    DaDanhGia = db.CT_HOADON
                                    .Where(ct => ct.MaHD == d.MaHD)
                                    .All(ct => db.DANHGIAs.Any(dg => dg.MaKH == kh.MaKH && dg.MaSP == ct.MaSP))
                })
                .ToList();

            return View(dsDonHang);
        }

        public ActionResult CT_LichSu(int id)
        {
            var kh = Session["user"] as NGUOIDUNG;

            // 1. Bắt đăng nhập
            if (kh == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            // 2. Lấy hóa đơn
            var hd = db.HOADONs.FirstOrDefault(h => h.MaHD == id);
            if (hd == null)
                return HttpNotFound();

            // 3. Check quyền: chỉ chủ đơn hoặc Admin mới xem được
            if (hd.MaKH != kh.MaKH && kh.VaiTro != "Admin")
                return new HttpStatusCodeResult(403, "Bạn không có quyền xem đơn hàng này.");

            // 4. Map CT_HOADON -> ViewModel đúng kiểu mà view cần
            var chiTietVm = db.CT_HOADON
                .Where(ct => ct.MaHD == id)
                .Select(ct => new ChiTietHoaDonViewModel
                {
                    MaHD = ct.MaHD,
                    MaSP = ct.MaSP,
                    TenSP = ct.SANPHAM.TenSP,
                    SoLuong = ct.SoLuong,
                    ThanhTien = ct.ThanhTien,
                    TrangThaiCT = ct.TrangThaiCT,
                    DaDanhGia = db.DANHGIAs.Any(d => d.MaHD == ct.MaHD && d.MaSP == ct.MaSP),
                    DaKhieuNai = db.KHIEUNAIs.Any(k => k.MaSP == ct.MaHD && k.MaSP == ct.MaSP)
                })
                .ToList();

            // 5. Đưa HOADON sang view bằng ViewBag (view của bạn đang dùng ViewBag.HoaDon)
            ViewBag.HoaDon = hd;

            // 6. Trả đúng kiểu model mà view khai báo
            return View(chiTietVm);
        }

        [HttpGet]
        public ActionResult HuyDonHang(int id)
        {
            var kh = Session["user"] as NGUOIDUNG;
            if (kh == null)
            {
                TempData["ThongBao"] = "Vui lòng đăng nhập để thực hiện!";
                return RedirectToAction("DangNhap", "TaiKhoan");
            }

            var hd = db.HOADONs.FirstOrDefault(d => d.MaHD == id && d.MaKH == kh.MaKH);
            if (hd == null)
            {
                return HttpNotFound();
            }

            if (hd.TrangThai == "Đang chờ xử lý")
            {
                var chiTiet = db.CT_HOADON.Where(ct => ct.MaHD == hd.MaHD).ToList();

                foreach (var item in chiTiet)
                {
                    // Trả lại kho
                    var sp = db.SANPHAMs.Find(item.MaSP);
                    if (sp != null)
                    {
                        sp.SoLuong += item.SoLuong;

                        if (sp.TrangThai == "Đã bán" && sp.SoLuong > 0)
                            sp.TrangThai = "Đã duyệt";
                    }

                    // 🔥 Update trạng thái chi tiết
                    item.TrangThaiCT = "Đã Huỷ".Normalize();
                    // <-- chữ H và chữ Y CHUẨN với DB
                }

                // 🔥 Update trạng thái hóa đơn
                hd.TrangThai = "Đã Huỷ";

                db.SaveChanges();

                TempData["ThongBao"] = "Đơn hàng đã được hủy thành công!";
            }
            else
            {
                TempData["ThongBao"] = "Đơn hàng không thể hủy vì đã giao hoặc hoàn tất!";
            }

            return RedirectToAction("LichSu");
        }


        [HttpGet]
        public ActionResult SuaDonHang(int id)
        {
            var kh = Session["user"] as NGUOIDUNG;
            if (kh == null)
            {
                return RedirectToAction("DangNhap", "TaiKhoan");
            }

            var hd = db.HOADONs.FirstOrDefault(d => d.MaHD == id && d.MaKH == kh.MaKH);
            if (hd == null)
            {
                return HttpNotFound();
            }

            return View(hd);
        }

        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult SuaDonHang(HOADON model)
        {
            var kh = Session["user"] as NGUOIDUNG;
            if (kh == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            var hd = db.HOADONs.Include("CT_HOADON")
                       .FirstOrDefault(d => d.MaHD == model.MaHD && d.MaKH == kh.MaKH);
            if (hd == null)
                return HttpNotFound();

            // Cập nhật số lượng từ model (nếu cần)
            foreach (var ctModel in model.CT_HOADON)
            {
                var ct = hd.CT_HOADON.FirstOrDefault(c => c.MaSP == ctModel.MaSP);
                if (ct != null)
                {
                    var sp = db.SANPHAMs.Find(ct.MaSP);
                    if (sp == null)
                    {
                        ModelState.AddModelError("", $"Sản phẩm {ct.MaSP} không tồn tại!");
                        return View(hd);
                    }

                    // Kiểm tra tồn kho
                    if (ctModel.SoLuong > sp.SoLuong + ct.SoLuong)
                    {
                        ModelState.AddModelError("",
                            $"Số lượng sản phẩm '{sp.TenSP}' không đủ. Tồn kho: {sp.SoLuong + ct.SoLuong}");
                        return View(hd);
                    }

                    ct.SoLuong = ctModel.SoLuong;
                    ct.ThanhTien = (decimal)(ct.SoLuong * sp.Gia);
                }
            }

            // Cập nhật thông tin HOADON
            hd.PhuongThucTT = model.PhuongThucTT;
            hd.DiaChiGiaoHang = model.DiaChiGiaoHang;

            db.SaveChanges();
            TempData["ThongBao"] = "Cập nhật đơn hàng thành công!";
            return RedirectToAction("LichSu");
        }
        // GET: Hiển thị form đánh giá
        //public ActionResult DanhGia(int maHD, int maSP)
        //{

        //    var ct = db.CT_HOADON
        //               .Where(x => x.MaHD == maHD && x.MaSP == maSP)
        //               .Select(x => new DanhGiaViewModel
        //               {
        //                   MaHD = x.MaHD,
        //                   MaSP = x.MaSP,
        //                   TenSP = x.SANPHAM.TenSP,
        //                   Hinh = db.HINHANHSPs
        //                            .Where(h => h.Masp == x.MaSP && h.AnhBia == true)
        //                            .Select(h => h.URLAnh)
        //                            .FirstOrDefault(),
        //                   DaDanhGia = x.DaDanhGia
        //               })
        //               .FirstOrDefault();

        //    if (ct == null)
        //        return HttpNotFound();

        //    return View(ct);
        //}

        public ActionResult DanhGia(int maHD, int maSP)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            var ct = db.CT_HOADON
                       .FirstOrDefault(x =>
                           x.MaHD == maHD &&
                           x.MaSP == maSP &&
                           x.HOADON.MaKH == user.MaKH);

            if (ct == null)
            {
                return new HttpStatusCodeResult(403, "Bạn không có quyền đánh giá!");
            }

            if (ct.TrangThaiCT != "Đã xác nhận")
                return new HttpStatusCodeResult(403, "Chỉ đánh giá sau khi đơn hoàn thành");


            var vm = new DanhGiaViewModel
            {
                MaHD = ct.MaHD,
                MaSP = ct.MaSP,
                TenSP = ct.SANPHAM.TenSP,
                Hinh = db.HINHANHSPs
                            .Where(h => h.Masp == maSP && h.AnhBia == true)
                            .Select(h => h.URLAnh)
                            .FirstOrDefault(),
                DaDanhGia = ct.DaDanhGia
            };

            return View(vm);
        }

        // POST: Lưu đánh giá
        //[HttpPost]
        //[ValidateAntiForgeryToken]
        //public ActionResult DanhGia(int maHD, int maSP, int soSao, string noiDung)
        //{
        //    var kh = Session["user"] as NGUOIDUNG;
        //    if (kh == null)
        //        return RedirectToAction("DangNhap", "TaiKhoan");

        //    var danhGia = new DANHGIA
        //    {
        //        MaKH = kh.MaKH,
        //        MaSP = maSP,
        //        MaHD = maHD,
        //        SoSao = soSao,
        //        NoiDung = noiDung,
        //        NgayDG = DateTime.Now
        //    };
        //    db.DANHGIAs.Add(danhGia);
        //    db.SaveChanges();

        //    TempData["ThongBao"] = "✅ Cảm ơn bạn đã đánh giá sản phẩm!";
        //    return RedirectToAction("LichSu");
        //}

        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult DanhGia(int maHD, int maSP, int soSao, string noiDung)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            var ct = db.CT_HOADON
                       .FirstOrDefault(x =>
                           x.MaHD == maHD &&
                           x.MaSP == maSP &&
                           x.HOADON.MaKH == user.MaKH);

            if (ct == null)
                return new HttpStatusCodeResult(403);

            if (ct.TrangThaiCT != "Đã xác nhận")
                return new HttpStatusCodeResult(403);

            
            var dg = new DANHGIA
            {
                MaKH = user.MaKH,
                MaHD = maHD,
                MaSP = maSP,
                SoSao = soSao,
                NoiDung = noiDung,
                NgayDG = DateTime.Now
            };

            db.DANHGIAs.Add(dg);

            ct.DaDanhGia = true;

            db.SaveChanges();

            TempData["ThongBao"] = "✅ Cảm ơn bạn đã đánh giá!";
            return RedirectToAction("LichSu");
        }

        public ActionResult KhieuNai()
        {
            var kh = Session["user"] as NGUOIDUNG;
            if (kh == null)
                return RedirectToAction("DangNhap", "TaiKhoan");
            var dsKhieuNai = db.KHIEUNAIs
                .Where(kn => kn.SANPHAM.MaKH == kh.MaKH)
                .OrderByDescending(kn => kn.NgayGui)
                .Select(kn => new KhieuNaiViewModel
                {
                    MaKN = kn.MaKN,
                    MaKH = kn.MaKH.Value,
                    TenNguoiGui = kn.NGUOIDUNG.HoTen,
                    MaSP = kn.MaSP.Value,
                    TenSP = kn.SANPHAM.TenSP,
                    MoTa = kn.MoTa,
                    NgayGui = kn.NgayGui ?? DateTime.Now,
                    TrangThai = kn.TrangThai
                })
                .ToList();

            if (dsKhieuNai == null)
            {
                TempData["ThongBao"] = "🎉 Tuyệt vời! Hiện chưa có khiếu nại nào về sản phẩm của bạn.";
            }

            return View(dsKhieuNai);
        }
    }
}