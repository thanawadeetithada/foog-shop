document.addEventListener("DOMContentLoaded", () => {
    const toggles = document.querySelectorAll(".toggle-status");

    toggles.forEach(toggle => {
        toggle.addEventListener("change", (e) => {
            const productId = e.target.dataset.id;
            const isAvailable = e.target.checked;

            fetch("update_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ id: productId, is_available: isAvailable }),
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert("สถานะสินค้าได้รับการอัปเดต");
                      location.reload();
                  } else {
                      alert("เกิดข้อผิดพลาด");
                  }
              });
        });
    });
});

