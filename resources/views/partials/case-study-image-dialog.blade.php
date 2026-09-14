<dialog
    id="case-study-image-dialog"
    class="case-study-image-dialog"
    aria-labelledby="case-study-image-dialog-title"
    aria-describedby="case-study-image-dialog-description"
>
    <div class="case-study-image-dialog__content">
        <header class="case-study-image-dialog__header">
            <div>
                <p class="case-study-image-dialog__eyebrow">HÌNH ẢNH MINH CHỨNG</p>
                <h2 id="case-study-image-dialog-title">Xem ảnh minh chứng</h2>
                <p id="case-study-image-dialog-description">Bạn có thể đóng ảnh bất kỳ lúc nào bằng nút Đóng hoặc phím Esc.</p>
            </div>
            <form method="dialog">
                <button class="case-study-image-dialog__close" type="submit" aria-label="Đóng ảnh phóng to">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    <span>Đóng</span>
                </button>
            </form>
        </header>

        <figure class="case-study-image-dialog__figure">
            <img id="case-study-image-dialog-image" src="" alt="">
            <figcaption id="case-study-image-dialog-caption" hidden></figcaption>
        </figure>

        <footer class="case-study-image-dialog__footer">
            <p id="case-study-image-dialog-count" aria-live="polite"></p>
            <nav class="case-study-image-dialog__controls" aria-label="Điều hướng ảnh">
                <button type="button" data-case-study-image-prev>Ảnh trước</button>
                <button type="button" data-case-study-image-next>Ảnh tiếp theo</button>
            </nav>
        </footer>
    </div>
</dialog>
