function draw_line(ctx, x1, y1, x2, y2, w, c, a) {
    ctx.beginPath();
    ctx.lineWidth = w;
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.strokeStyle = c;
    ctx.globalAlpha = a;
    ctx.shadowBlur = 6;
    ctx.shadowOffsetX = 1;
    ctx.shadowOffsetY = 1;
    ctx.shadowColor = "#222";
    ctx.stroke();
}

function draw_arc(ctx, cx, cy, r, c, a) {
    ctx.beginPath();
    ctx.fillStyle = c;
    ctx.globalAlpha = a;
    ctx.shadowBlur = 8;
    ctx.shadowOffsetX = 1;
    ctx.shadowOffsetY = 1;
    ctx.shadowColor = "#222";
    ctx.arc(cx, cy, r, 0, 2 * Math.PI);
    ctx.fill();
    ctx.stroke();
}

function draw_hour(ctx, cx, cy) {
    var hours = new Date().getHours();
    var minutes = new Date().getMinutes();
    rad = 30 * ((hours - 3) + (minutes / 60)) * Math.PI / 180;
    ex = cx + 24 * Math.cos(rad);
    ey = cy + 24 * Math.sin(rad);
    draw_line(ctx, cx, cy, ex, ey, 5, '#29B5F2', 1);
}

function draw_minute(ctx, cx, cy) {
    var minutes = new Date().getMinutes();
    rad = 6 * (minutes - 15) * Math.PI / 180;
    ex = cx + 28 * Math.cos(rad);
    ey = cy + 28 * Math.sin(rad);
    draw_line(ctx, cx, cy, ex, ey, 4, '#29B5F2', 1);
}

function draw_second(ctx, cx, cy) {
    var seconds = new Date().getSeconds();
    rad = 6 * (seconds - 15) * Math.PI / 180;
    bx = cx - 12 * Math.cos(rad);
    by = cy - 12 * Math.sin(rad);
    ex = cx + 34 * Math.cos(rad);
    ey = cy + 34 * Math.sin(rad);
    draw_line(ctx, bx, by, ex, ey, 2, '#FF0000', 1);

    draw_arc(ctx, cx, cy, 5, '#FF0000', 1);
}

function draw_hands() {
    var canvas = $("#clockhands")[0];
    var ctx = canvas.getContext('2d');
    var cx = canvas.width / 2 - 1;
    var cy = canvas.height / 2 - 1;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    draw_hour(ctx, cx, cy);
    draw_minute(ctx, cx, cy);
    draw_second(ctx, cx, cy);
}

$(document).ready(function () {
    $("#clockface").hover(function () {
        $("#clock").toggleClass('opened');
        $("#calendar").toggleClass('opened');
    });

    draw_hands();
    setInterval(function () {
        draw_hands();
    }, 1000);
});

