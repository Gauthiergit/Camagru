<div class="camera-container">
	<div id="toast-container" class="toast-container"></div>
	<h2>Studio Photo</h2>
    <p id="camera-error" class="alert alert-danger" style="display:none; margin-bottom: 10px;"></p>
    <div class="main-capture" style="position:relative; width:640px; max-width:100%;">
        <video id="video" width="640" height="480" autoplay playsinline style="display:none";></video>
        <canvas id="renderCanvas" width="640" height="480" style="border:1px solid black; cursor:move;"></canvas>
    </div>

    <div class="stickers-selector">
        <h3>1. Choisis ton accessoire</h3>
        <div class="stickers-grid">
            <label>
                <input type="radio" name="sticker" value="chapeau.png">
                <img src="/assets/stickers/chapeau.png" width="100">
            </label>
            <label>
                <input type="radio" name="sticker" value="lunettes.png">
                <img src="/assets/stickers/lunettes.png" width="100">
            </label>
            <label>
                <input type="radio" name="sticker" value="barbe_hippie.png">
                <img src="/assets/stickers/barbe_hippie.png" width="100">
            </label>
            <label>
                <input type="radio" name="sticker" value="chat_effraye.png">
                <img src="/assets/stickers/chat_effraye.png" width="100">
            </label>
            <label>
                <input type="radio" name="sticker" value="chat_volant.png">
                <img src="/assets/stickers/chat_volant.png" width="100">
            </label>
        </div>
    </div>

    <div class="controls">
        <button id="snap" class="btn-primary" disabled>2. Prendre la photo</button>
    </div>

    <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
</div>

<script type="module" src="/js/camera.js"></script>