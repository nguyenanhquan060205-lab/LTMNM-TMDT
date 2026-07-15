using System;
using System.Linq;
using System.IO;
using System.Web;
using System.Web.Mvc;
using ThuongMaiDienTu_DoAn.Models;

namespace ThuongMaiDienTu_DoAn.Controllers
{
    public class TinNhanController : Controller
    {
        TMDTEntities db = new TMDTEntities();

        // ==========================
        // HIỂN THỊ GIAO DIỆN CHAT
        // ==========================
        //public ActionResult Chat(int? idNguoiNhan, int? maSP, string anhSP)
        //{
        //    ViewBag.MaSP = maSP;
        //    if (maSP.HasValue)
        //    {

        //        var anhBia = db.HINHANHSPs
        //            .Where(h => h.Masp == maSP.Value && h.AnhBia == true)
        //            .Select(h => h.URLAnh)
        //            .FirstOrDefault();

        //        // nếu không có ảnh bìa → lấy ảnh đầu tiên
        //        if (anhBia == null)
        //        {
        //            anhBia = db.HINHANHSPs
        //                .Where(h => h.Masp == maSP.Value)
        //                .Select(h => h.URLAnh)
        //                .FirstOrDefault();
        //        }

        //        ViewBag.AnhSP = anhBia;

        //        var tenSP = db.SANPHAMs
        //                .Where(s => s.MaSP == maSP.Value)
        //                .Select(s => s.TenSP)
        //                .FirstOrDefault();

        //        ViewBag.TenSP = tenSP;
        //    }

        //    var currentUser = Session["user"] as NGUOIDUNG;
        //    if (currentUser == null)
        //        return RedirectToAction("DangNhap", "TaiKhoan");

        //    var admin = db.NGUOIDUNGs.FirstOrDefault(u => u.VaiTro == "Admin");

        //    // Danh sách người đã chat với mình
        //    var listNguoiDung = db.TINNHANs
        //        .Where(t => t.NguoiGui == currentUser.MaKH || t.NguoiNhan == currentUser.MaKH)
        //        .Select(t => t.NguoiGui == currentUser.MaKH ? t.NGUOIDUNG1 : t.NGUOIDUNG)
        //        .Distinct()
        //        .ToList();

        //    // User thường → ép admin vào đầu danh sách
        //    if (currentUser.VaiTro != "Admin" && admin != null)
        //    {
        //        if (!listNguoiDung.Any(u => u.MaKH == admin.MaKH))
        //            listNguoiDung.Insert(0, admin);
        //    }

        //    // Admin → thấy toàn bộ user
        //    if (currentUser.VaiTro == "Admin")
        //    {
        //        listNguoiDung = db.NGUOIDUNGs
        //            .Where(u => u.MaKH != currentUser.MaKH)
        //            .ToList();
        //    }

        //    var userChuaDoc = db.TINNHANs
        //        .Where(t => t.NguoiNhan == currentUser.MaKH && t.DaDoc == false)
        //        .Select(t => t.NguoiGui)
        //        .Distinct()
        //        .Where(id => listNguoiDung.Any(u => u.MaKH == id))
        //        .ToList();

        //    ViewBag.UserChuaDoc = userChuaDoc;

        //    // Nếu chưa chọn người để nhắn
        //    if (idNguoiNhan == null)
        //    {
        //        ViewBag.NguoiNhanID = 0;
        //        ViewBag.NguoiNhanTen = "Chưa chọn người để trò chuyện";
        //        ViewBag.NguoiGuiID = currentUser.MaKH;
        //        return View(listNguoiDung);
        //    }

        //    var userNhan = db.NGUOIDUNGs.Find(idNguoiNhan);
        //    if (userNhan == null)
        //        return HttpNotFound();

        //    ViewBag.NguoiNhanID = userNhan.MaKH;
        //    ViewBag.NguoiNhanTen = userNhan.HoTen;
        //    ViewBag.NguoiGuiID = currentUser.MaKH;

        //    return View(listNguoiDung);
        //}

        public ActionResult Chat(int? idNguoiNhan, int? maSP, string anhSP)
        {
            ViewBag.MaSP = maSP;

            if (maSP.HasValue)
            {
                var anhBia = db.HINHANHSPs
                    .Where(h => h.Masp == maSP.Value && h.AnhBia == true)
                    .Select(h => h.URLAnh)
                    .FirstOrDefault();

                if (anhBia == null)
                {
                    anhBia = db.HINHANHSPs
                        .Where(h => h.Masp == maSP.Value)
                        .Select(h => h.URLAnh)
                        .FirstOrDefault();
                }

                ViewBag.AnhSP = anhBia;

                ViewBag.TenSP = db.SANPHAMs
                    .Where(s => s.MaSP == maSP.Value)
                    .Select(s => s.TenSP)
                    .FirstOrDefault();
            }

            // ================== CHECK LOGIN ==================
            var currentUser = Session["user"] as NGUOIDUNG;
            if (currentUser == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            var admin = db.NGUOIDUNGs.FirstOrDefault(u => u.VaiTro == "Admin");

            // ================== DANH SÁCH NGƯỜI ĐÃ CHAT ==================
            var listNguoiDung = db.TINNHANs
                .Where(t => t.NguoiGui == currentUser.MaKH || t.NguoiNhan == currentUser.MaKH)
                .Select(t => t.NguoiGui == currentUser.MaKH ? t.NGUOIDUNG1 : t.NGUOIDUNG)
                .Distinct()
                .ToList();

            // User thường → ép admin lên đầu
            if (currentUser.VaiTro != "Admin" && admin != null)
            {
                if (!listNguoiDung.Any(u => u.MaKH == admin.MaKH))
                    listNguoiDung.Insert(0, admin);
            }

            // Admin → thấy toàn bộ user
            if (currentUser.VaiTro == "Admin")
            {
                listNguoiDung = db.NGUOIDUNGs
                    .Where(u => u.MaKH != currentUser.MaKH)
                    .ToList();
            }

            // ================== FIX LỖI EF: CHỈ DÙNG PRIMITIVE ==================
            var listNguoiDungIds = listNguoiDung
                .Select(u => u.MaKH)
                .ToList();

            var userChuaDoc = db.TINNHANs
                .Where(t =>
                    t.NguoiNhan == currentUser.MaKH &&
                    t.DaDoc == false &&
                    t.NguoiGui.HasValue &&
                    listNguoiDungIds.Contains(t.NguoiGui.Value)
                )
                .Select(t => t.NguoiGui.Value)
                .Distinct()
                .ToList();

            ViewBag.UserChuaDoc = userChuaDoc;

            if (idNguoiNhan == null)
            {
                ViewBag.NguoiNhanID = 0;
                ViewBag.NguoiNhanTen = "Chưa chọn người để trò chuyện";
                ViewBag.NguoiGuiID = currentUser.MaKH;
                return View(listNguoiDung);
            }

            var userNhan = db.NGUOIDUNGs.Find(idNguoiNhan);
            if (userNhan == null)
                return HttpNotFound();

            ViewBag.NguoiNhanID = userNhan.MaKH;
            ViewBag.NguoiNhanTen = userNhan.HoTen;
            ViewBag.NguoiGuiID = currentUser.MaKH;

            return View(listNguoiDung);
        }


        // ==========================
        // LOAD TIN NHẮN + UPDATE "ĐÃ XEM"
        // ==========================
        public ActionResult LoadTinNhan(int idNguoiGui, int idNguoiNhan)
        {
            // Lấy toàn bộ đoạn chat
            var list = db.TINNHANs.AsNoTracking()
                .Where(t =>
                    (t.NguoiGui == idNguoiGui && t.NguoiNhan == idNguoiNhan) ||
                    (t.NguoiGui == idNguoiNhan && t.NguoiNhan == idNguoiGui))
                .OrderBy(t => t.NgayGui)
                .ToList()
                .Select(t =>
                {
                    // ==== FIX AVATAR TỰ ĐỘNG GIỐNG LAYOUT ADMIN ====
                    string avatar = string.IsNullOrEmpty(t.NGUOIDUNG.AnhDaiDien)
                        ? "Default.jpg"
                        : t.NGUOIDUNG.AnhDaiDien;

                    string avatarPath = Server.MapPath("~/Content/avatars/" + avatar);
                    if (!System.IO.File.Exists(avatarPath))
                    {
                        avatar = "Default.jpg";
                    }

                    return new
                    {
                        MaTN = t.MaTN,
                        t.NguoiGui,
                        t.NguoiNhan,
                        NoiDung = t.NoiDung,
                        Anh = t.Anh,
                        Gio = t.NgayGui.HasValue ? t.NgayGui.Value.ToString("HH:mm dd/MM") : "",
                        AvatarGui = avatar,     // <<< avatar đã kiểm tra tồn tại
                        t.DaDoc                  // giữ trạng thái đã đọc
                    };
                });


            return Json(list, JsonRequestBehavior.AllowGet);
        }

        // ==========================
        // GỬI TIN NHẮN
        // ==========================
        //[HttpPost]
        //public ActionResult GuiTinNhan(int nguoiGui, int nguoiNhan, string noiDung)
        //{
        //    if (string.IsNullOrWhiteSpace(noiDung))
        //        return new HttpStatusCodeResult(400, "Nội dung trống");

        //    var tin = new TINNHAN
        //    {
        //        NguoiGui = nguoiGui,
        //        NguoiNhan = nguoiNhan,
        //        NoiDung = noiDung.Trim(),
        //        NgayGui = DateTime.Now,
        //        DaDoc = false
        //    };

        //    db.TINNHANs.Add(tin);
        //    db.SaveChanges();

        //    return new HttpStatusCodeResult(200);
        //}

        [HttpPost]
        public ActionResult GuiTinNhan(int nguoiGui, int nguoiNhan, string noiDung, HttpPostedFileBase anh)
        {
            if (string.IsNullOrWhiteSpace(noiDung) && anh == null)
                return new HttpStatusCodeResult(400, "Tin nhắn trống");

            string fileName = null;

            if (anh != null && anh.ContentLength > 0)
            {
                fileName = Guid.NewGuid() + Path.GetExtension(anh.FileName);
                string path = Server.MapPath("~/Content/chat_images/" + fileName);
                anh.SaveAs(path);
            }

            var tin = new TINNHAN
            {
                NguoiGui = nguoiGui,
                NguoiNhan = nguoiNhan,
                NoiDung = string.IsNullOrWhiteSpace(noiDung) ? null : noiDung.Trim(),
                Anh = fileName,
                NgayGui = DateTime.Now,
                DaDoc = false
            };

            db.TINNHANs.Add(tin);
            db.SaveChanges();

            return new HttpStatusCodeResult(200);
        }
        [HttpPost]
        public ActionResult DanhDauDaDoc(int idNguoiGui, int idNguoiNhan)
        {
            var unread = db.TINNHANs
                .Where(t =>
                    t.NguoiGui == idNguoiNhan &&
                    t.NguoiNhan == idNguoiGui &&
                    t.DaDoc == false)
                .ToList();

            foreach (var t in unread)
                t.DaDoc = true;

            db.SaveChanges();
            return new HttpStatusCodeResult(200);
        }

        [HttpPost]
        public ActionResult XoaTinNhan(int idTin)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null)
                return new HttpStatusCodeResult(401);

            var tin = db.TINNHANs.Find(idTin);
            if (tin == null)
                return HttpNotFound();

            if (tin.NguoiGui != user.MaKH && user.VaiTro != "Admin")
                return new HttpStatusCodeResult(403); // không có quyền

            tin.NoiDung = "Tin nhắn này đã được xóa";
            tin.Anh = null;
            db.SaveChanges();

            return RedirectToAction("Chat", new { idNguoiNhan = tin.NguoiNhan });
        }


    }
}
