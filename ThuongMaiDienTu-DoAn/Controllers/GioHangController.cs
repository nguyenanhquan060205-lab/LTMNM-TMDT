using ThuongMaiDienTu_DoAn.Models;
using System;
using System.Linq;
using System.Web.Mvc;
using System.Data.Entity;

namespace ThuongMaiDienTu_DoAn.Controllers
{
    public class GioHangController : Controller
    {
        TMDTEntities db = new TMDTEntities();

        // ------------------- 🛒 ICON GIỎ HÀNG -------------------
        [ChildActionOnly]
        public ActionResult CartIcon()
        {
            var user = Session["user"] as NGUOIDUNG;
            int tong = 0;

            if (user != null)
            {
                var gio = db.GIOHANGs.FirstOrDefault(g => g.MaKH == user.MaKH);
                if (gio != null)
                    tong = gio.TongSoLuong ?? 0;
            }

            ViewBag.TongSoLuong = tong;
            return PartialView("CartIcon");
        }

        // ------------------- 🧾 TRANG GIỎ HÀNG -------------------
        public ActionResult Index()
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null) return RedirectToAction("DangNhap", "TaiKhoan");

            var gio = db.GIOHANGs.FirstOrDefault(g => g.MaKH == user.MaKH);
            if (gio == null)
            {
                gio = new GIOHANG { MaKH = user.MaKH };
                db.GIOHANGs.Add(gio);
                db.SaveChanges();
            }

            var ds = db.CT_GIOHANG
                       .Where(c => c.MaGH == gio.MaGH)
                       .Include(c => c.SANPHAM)
                       .Include(c => c.SANPHAM.HINHANHSPs)
                       .ToList();

            return View(ds);
        }

        public ActionResult Them(int id)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null)
            {
                TempData["Error"] = "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.";
                return RedirectToAction("DangNhap", "TaiKhoan");
            }

 
            var sp = db.SANPHAMs.Find(id);
            if (sp == null || sp.TrangThai != "Đã duyệt")
            {
                TempData["Error"] = "Sản phẩm không tồn tại hoặc không còn được bán.";
                return RedirectToAction("Index", "SanPham");
            }

 
            if (sp.MaKH == user.MaKH)
            {
                TempData["Error"] = "Bạn không thể mua sản phẩm của chính mình!";
                return RedirectToAction("ChiTiet", "SanPham", new { id });
            }
 
            var gio = db.GIOHANGs.Include(g => g.CT_GIOHANG).FirstOrDefault(g => g.MaKH == user.MaKH);
            if (gio == null)
            {
                gio = new GIOHANG { MaKH = user.MaKH };
                db.GIOHANGs.Add(gio);
                db.SaveChanges();
            }

 
            var ctGioHang = gio.CT_GIOHANG.FirstOrDefault(c => c.MaSP == id);
            int soLuongHienTai = ctGioHang?.SoLuong ?? 0;
            int soLuongThem = 1;  

 
            int soLuongMoi = soLuongHienTai + soLuongThem;

 
            if (sp.SoLuong <= 0)
            {
                TempData["Error"] = "Sản phẩm này vừa hết hàng! Ai nhanh tay thì còn.";
                return RedirectToAction("ChiTiet", "SanPham", new { id });
            }

 
            if (soLuongMoi > sp.SoLuong)
            {
                TempData["Error"] = $"Sản phẩm '{sp.TenSP}' chỉ còn {sp.SoLuong} sản phẩm. Không thể thêm thêm.";
                return RedirectToAction("ChiTiet", "SanPham", new { id });
            }

 
            if (ctGioHang == null)
            {
                // Thêm mới
                db.CT_GIOHANG.Add(new CT_GIOHANG
                {
                    MaGH = gio.MaGH,
                    MaSP = id,
                    SoLuong = soLuongThem,
                    ThanhTien = sp.Gia * soLuongThem
                });
            }
            else
            {
 
                ctGioHang.SoLuong = soLuongMoi;
                ctGioHang.ThanhTien = sp.Gia * soLuongMoi;
            }

            db.SaveChanges();

            // 7. Cập nhật lại số lượng giỏ hàng trong session
            var gioUpdate = db.GIOHANGs.FirstOrDefault(g => g.MaKH == user.MaKH);
            Session["CartCount"] = gioUpdate?.TongSoLuong ?? 0;

            TempData["Success"] = "Đã thêm sản phẩm vào giỏ hàng!";
            return RedirectToAction("Index", "SanPham"); 
        }
        // TĂNG SỐ LƯỢNG 
        public ActionResult Tang(int id)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null) return RedirectToAction("DangNhap", "TaiKhoan");

            var gio = db.GIOHANGs.FirstOrDefault(g => g.MaKH == user.MaKH);
            if (gio == null) return RedirectToAction("Index");

            var ct = db.CT_GIOHANG.FirstOrDefault(c => c.MaGH == gio.MaGH && c.MaSP == id);
            var sp = db.SANPHAMs.Find(id);

            if (ct != null && sp != null)
            {
                if (ct.SoLuong < sp.SoLuong)
                {
                    ct.SoLuong++;
                    ct.ThanhTien = ct.SoLuong * sp.Gia;
                    db.SaveChanges();
                    Session["CartCount"] = gio.TongSoLuong ?? 0;
                }
                else
                {
                    TempData["CartWarning"] = $"⚠️ Sản phẩm '{sp.TenSP}' còn {sp.SoLuong} sản phẩm!";
                }
            }

            return RedirectToAction("Index");
        }

        //   GIẢM SỐ LƯỢNG 
        public ActionResult Giam(int id)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null) return RedirectToAction("DangNhap", "TaiKhoan");

            var gio = db.GIOHANGs.FirstOrDefault(g => g.MaKH == user.MaKH);
            if (gio == null) return RedirectToAction("Index");

            var ct = db.CT_GIOHANG.FirstOrDefault(c => c.MaGH == gio.MaGH && c.MaSP == id);
            if (ct != null)
            {
                var sp = db.SANPHAMs.Find(id);
                if (ct.SoLuong > 1)
                {
                    ct.SoLuong--;
                    ct.ThanhTien = ct.SoLuong * sp.Gia;
                }
                else
                {
                    db.CT_GIOHANG.Remove(ct);
                }
                db.SaveChanges();
                Session["CartCount"] = gio.TongSoLuong ?? 0;
            }

            return RedirectToAction("Index");
        }

        // XOÁ SẢN PHẨM 
        public ActionResult Xoa(int id)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null) return RedirectToAction("DangNhap", "TaiKhoan");

            var gio = db.GIOHANGs.FirstOrDefault(g => g.MaKH == user.MaKH);
            if (gio == null) return RedirectToAction("Index");

            var ct = db.CT_GIOHANG.FirstOrDefault(c => c.MaGH == gio.MaGH && c.MaSP == id);
            if (ct != null)
            {
                db.CT_GIOHANG.Remove(ct);
                db.SaveChanges();
                Session["CartCount"] = gio.TongSoLuong ?? 0;
            }

            TempData["CartOK"] = "Đã xóa sản phẩm khỏi giỏ hàng.";
            return RedirectToAction("Index");
        }
    }
}
