//using System;
//using System.Data.Entity;
//using System.IO;
//using System.Linq;
//using System.Web.Mvc;
//using ThuongMaiDienTu_DoAn.Filters;
//using ThuongMaiDienTu_DoAn.Models;

//namespace ThuongMaiDienTu_DoAn.Controllers
//{
//    [AuthorizeAdmin]
//    public class AdminController : Controller
//    {
//        private readonly TMDTEntities db = new TMDTEntities();

//        // === DASHBOARD ===
//        public ActionResult Index()
//        {
//            ViewBag.TongNguoiDung = db.NGUOIDUNGs.Count();
//            ViewBag.TongSanPham = db.SANPHAMs.Count();
//            ViewBag.TinChoDuyet = db.SANPHAMs.Count(sp => sp.TrangThai == "Chờ duyệt");
//            ViewBag.TinDaDuyet = db.SANPHAMs.Count(sp => sp.TrangThai == "Đã duyệt");
//            return View();
//        }

//        // === DUYỆT TIN / SẢN PHẨM ===


//        [HttpPost]
//        public ActionResult DoiTrangThai(int id, string tt)
//        {
//            var sp = db.SANPHAMs.Find(id);
//            if (sp == null)
//                return HttpNotFound();

//            sp.TrangThai = tt;
//            db.SaveChanges();

//            TempData["Success"] = $"✅ Đã cập nhật trạng thái sản phẩm **{sp.TenSP}** thành '{tt}'";
//            return RedirectToAction("QuanLySanPham");
//        }

//        // === QUẢN LÝ NGƯỜI DÙNG ===
//        public ActionResult QuanLyNguoiDung()
//        {
//            var ds = db.NGUOIDUNGs.OrderByDescending(x => x.NgayTao).ToList();
//            return View(ds);
//        }

//        // === QUẢN LÝ SẢN PHẨM ===
//        public ActionResult QuanLySanPham()
//        {
//            // 1. Lấy dữ liệu Sản phẩm
//            var listSP = db.SANPHAMs
//                .Include(s => s.NGUOIDUNG)
//                .OrderByDescending(x => x.NgayTao)
//                .ToList();

//            // 2. Lấy dữ liệu Loại (kèm sản phẩm con)
//            var listLoai = db.LOAISANPHAMs.Include("SANPHAMs").ToList();

//            // 3. Đổ vào ViewModel (lúc này nó tự hiểu class nằm trong thư mục Models)
//            var model = new AdminProductViewModel
//            {
//                SanPhams = listSP,
//                LoaiSanPhams = listLoai
//            };

//            return View(model);
//        }

//        // === QUẢN LÝ ĐƠN HÀNG ===
//        public ActionResult QuanLyDonHang()
//        {
//            var donhangs = db.HOADONs
//                .Select(hd => new DonHangC2CViewModel
//                {
//                    MaHD = hd.MaHD,
//                    NguoiMua = hd.NGUOIDUNG.HoTen,
//                    NguoiBan = hd.CT_HOADON
//                                 .Select(ct => ct.SANPHAM.NGUOIDUNG.HoTen)
//                                 .FirstOrDefault() ?? "(Chưa có sản phẩm)",
//                    NgayDat = hd.NgayDat,
//                    TongTien = (decimal)hd.TongTien,
//                    TrangThai = hd.TrangThai
//                })
//                .ToList();

//            return View(donhangs);
//        }

//        // === QUẢN LÝ KHIẾU NẠI ===
//        public ActionResult QuanLyKhieuNai()
//        {
//            var dsKhieuNai = db.KHIEUNAIs
//                .Include("NGUOIDUNG")
//                .Include("SANPHAM")
//                .OrderByDescending(k => k.NgayGui)
//                .ToList();

//            return View(dsKhieuNai);
//        }


//        [HttpPost]
//        public ActionResult CapNhatTrangThaiKN(int id, string trangThai)
//        {
//            var user = Session["user"] as NGUOIDUNG;
//            if (user == null || user.VaiTro != "Admin")
//                return new HttpStatusCodeResult(403, "Không có quyền.");

//            var kn = db.KHIEUNAIs.Find(id);
//            if (kn == null)
//                return HttpNotFound();

//            kn.TrangThai = trangThai;
//            db.SaveChanges();

//            return Json(new { ok = true, status = kn.TrangThai });
//        }

//        // [POST] XÓA SẢN PHẨM TỪ ADMIN
//        [HttpPost]
//        public ActionResult Xoa(int id)
//        {
//            var sp = db.SANPHAMs.Find(id);
//            if (sp == null)
//            {
//                TempData["Error"] = "Sản phẩm không tồn tại!";
//                return RedirectToAction("QuanLySanPham");
//            }

//            try
//            {
//                // 1. Xóa ảnh trên server
//                var hinhAnhs = db.HINHANHSPs.Where(h => h.Masp == id).ToList();
//                string path = Server.MapPath("~/Content/Images/");
//                foreach (var item in hinhAnhs)
//                {
//                    string fullPath = Path.Combine(path, item.URLAnh);
//                    if (System.IO.File.Exists(fullPath) && item.URLAnh != "noimage.jpg")
//                    {
//                        System.IO.File.Delete(fullPath);
//                    }
//                }

//                // 2. Xóa dữ liệu trong DB (Xóa ảnh trước -> Xóa SP sau)
//                db.HINHANHSPs.RemoveRange(hinhAnhs);
//                db.SANPHAMs.Remove(sp);
//                db.SaveChanges();

//                TempData["Success"] = "🗑️ Đã xóa sản phẩm vĩnh viễn!";
//            }
//            catch (Exception)
//            {
//                // Trường hợp lỗi do ràng buộc khóa ngoại (đã có đơn hàng, đánh giá...)
//                // Ta chuyển sang trạng thái Ẩn thay vì xóa
//                sp.TrangThai = "Ẩn";
//                db.SaveChanges();
//                TempData["Success"] = "⚠️ Sản phẩm đã có đơn hàng, không thể xóa hẳn. Hệ thống đã chuyển sang trạng thái 'Ẩn'.";
//            }

//            return RedirectToAction("QuanLySanPham");
//        }
//    }
//}

using Org.BouncyCastle.Asn1.Ocsp;
using System;
using System.Data.Entity;
using System.IO;
using System.Linq;
using System.Web.Mvc;
using ThuongMaiDienTu_DoAn.Filters;
using ThuongMaiDienTu_DoAn.Models;

namespace ThuongMaiDienTu_DoAn.Controllers
{
    [AuthorizeAdmin]
    public class AdminController : Controller
    {
        private readonly TMDTEntities db = new TMDTEntities();

        // === DASHBOARD ===
        public ActionResult Index()
        {
            ViewBag.TongNguoiDung = db.NGUOIDUNGs.Count();
            ViewBag.TongSanPham = db.SANPHAMs.Count();
            ViewBag.Daban = db.SANPHAMs.Count(sp => sp.TrangThai == "Đã bán");
            ViewBag.An = db.SANPHAMs.Count(sp => sp.TrangThai == "Ẩn");
            ViewBag.TinDaDuyet = db.SANPHAMs.Count(sp => sp.TrangThai == "Đã duyệt");
            return View();
        }

        // === DUYỆT TIN / SẢN PHẨM ===


        [HttpPost]
        public ActionResult DoiTrangThai(int id, string tt)
        {
            var sp = db.SANPHAMs.Find(id);
            if (sp == null)
                return HttpNotFound();

            sp.TrangThai = tt;
            db.SaveChanges();

            TempData["Success"] = $"✅ Đã cập nhật trạng thái sản phẩm **{sp.TenSP}** thành '{tt}'";
            return RedirectToAction("QuanLySanPham");
        }

        // === QUẢN LÝ NGƯỜI DÙNG ===
        public ActionResult QuanLyNguoiDung()
        {
            var ds = db.NGUOIDUNGs.OrderByDescending(x => x.NgayTao).ToList();
            return View(ds);
        }

        // === QUẢN LÝ SẢN PHẨM ===
        public ActionResult QuanLySanPham()
        {
            // 1. Lấy dữ liệu Sản phẩm
            var listSP = db.SANPHAMs
                .Include(s => s.NGUOIDUNG)
                .OrderByDescending(x => x.NgayTao)
                .ToList();

            // 2. Lấy dữ liệu Loại (kèm sản phẩm con)
            var listLoai = db.LOAISANPHAMs.Include("SANPHAMs").ToList();

            // 3. Đổ vào ViewModel (lúc này nó tự hiểu class nằm trong thư mục Models)
            var model = new AdminProductViewModel
            {
                SanPhams = listSP,
                LoaiSanPhams = listLoai
            };

            return View(model);
        }

        // === QUẢN LÝ ĐƠN HÀNG ===
        public ActionResult QuanLyDonHang()
        {
            var donhangs = db.HOADONs
                .Select(hd => new DonHangC2CViewModel
                {
                    MaHD = hd.MaHD,
                    NguoiMua = hd.NGUOIDUNG.HoTen,
                    NguoiBan = hd.CT_HOADON
                                 .Select(ct => ct.SANPHAM.NGUOIDUNG.HoTen)
                                 .FirstOrDefault() ?? "(Chưa có sản phẩm)",
                    NgayDat = hd.NgayDat,
                    TongTien = (decimal)hd.TongTien,
                    TrangThai = hd.TrangThai
                })
                .ToList();

            return View(donhangs);
        }

        // === QUẢN LÝ KHIẾU NẠI ===
        public ActionResult QuanLyKhieuNai()
        {
            var dsKhieuNai = db.KHIEUNAIs
                .Include("NGUOIDUNG")
                .Include("SANPHAM")
                .OrderByDescending(k => k.NgayGui)
                .ToList();

            return View(dsKhieuNai);
        }


        [HttpPost]
        public ActionResult CapNhatTrangThaiKN(int id)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null || user.VaiTro != "Admin")
                return new HttpStatusCodeResult(403, "Không có quyền.");

            var kn = db.KHIEUNAIs.Find(id);
            if (kn == null)
                return HttpNotFound();


            kn.TrangThai = "Đã giải quyết";
            kn.PhanHoi = user.HoTen;
            db.SaveChanges();

            //return Json(new { Ma = id,ok = true, responder = kn.PhanHoi, status = kn.TrangThai });
            return RedirectToAction("QuanLyKhieuNai");
        }

        [HttpPost]
        [ValidateAntiForgeryToken]
        public ActionResult XoaKhieuNai(int id)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null || user.VaiTro != "Admin")
                return new HttpStatusCodeResult(403, "Không có quyền.");

            var kn = db.KHIEUNAIs.Find(id);
            if (kn == null)
                return HttpNotFound();

            db.KHIEUNAIs.Remove(kn);
            db.SaveChanges();

            TempData["Success"] = "Đã xoá khiếu nại thành công!";
            return RedirectToAction("QuanLyKhieuNai");
        }

        // [POST] XÓA SẢN PHẨM TỪ ADMIN
        [HttpPost]
        public ActionResult Xoa(int id)
        {
            var sp = db.SANPHAMs.Find(id);
            if (sp == null)
            {
                TempData["Error"] = "Sản phẩm không tồn tại!";
                return RedirectToAction("QuanLySanPham");
            }

            try
            {
                // 1. Xóa ảnh trên server
                var hinhAnhs = db.HINHANHSPs.Where(h => h.Masp == id).ToList();
                string path = Server.MapPath("~/Content/Images/");
                foreach (var item in hinhAnhs)
                {
                    string fullPath = Path.Combine(path, item.URLAnh);
                    if (System.IO.File.Exists(fullPath) && item.URLAnh != "noimage.jpg")
                    {
                        System.IO.File.Delete(fullPath);
                    }
                }

                // 2. Xóa dữ liệu trong DB (Xóa ảnh trước -> Xóa SP sau)
                db.HINHANHSPs.RemoveRange(hinhAnhs);
                db.SANPHAMs.Remove(sp);
                db.SaveChanges();

                TempData["Success"] = "🗑️ Đã xóa sản phẩm vĩnh viễn!";
            }
            catch (Exception)
            {
                // Trường hợp lỗi do ràng buộc khóa ngoại (đã có đơn hàng, đánh giá...)
                // Ta chuyển sang trạng thái Ẩn thay vì xóa
                sp.TrangThai = "Ẩn";
                db.SaveChanges();
                TempData["Success"] = "⚠️ Sản phẩm đã có đơn hàng, không thể xóa hẳn. Hệ thống đã chuyển sang trạng thái 'Ẩn'.";
            }

            return RedirectToAction("QuanLySanPham");
        }
        [HttpPost]
        public ActionResult DoiTrangThaiNguoiDung(int id)
        {
            var user = db.NGUOIDUNGs.FirstOrDefault(x => x.MaKH == id);
            if (user == null)
            {
                TempData["Error"] = "Không tìm thấy tài khoản!";
                return RedirectToAction("QuanLyNguoiDung");
            }

            // Không cho khóa tài khoản Admin
            if (user.VaiTro == "Admin")
            {
                TempData["Error"] = "Không thể khóa tài khoản Admin!";
                return RedirectToAction("QuanLyNguoiDung");
            }

            // Toggle BIT: 0 → 1 hoặc 1 → 0
            user.Khoa = !user.Khoa;

            db.SaveChanges();

            TempData["Success"] = user.Khoa
                ? "Tài khoản đã bị khóa!"
                : "Tài khoản đã được mở khóa!";

            return RedirectToAction("QuanLyNguoiDung");
        }

    }
}

