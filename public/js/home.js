// sidebar
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mobileDropdown = document.getElementById('mobileDropdown');

    sidebarToggle.addEventListener('click', function () {
        if (window.innerWidth <= 480) {
            mobileDropdown.classList.toggle('active');
        } else {
            sidebar.classList.toggle('collapsed');
        }
    });
});
// date
function formatDate(date) {
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString("id-ID", options);
}

var currentDate = new Date();
document.getElementById('tanggalRealtime').innerHTML = formatDate(currentDate);

//clock
function showTime(){
    var date = new Date();
    var h = date.getHours(); // 0 - 23
    var m = date.getMinutes(); // 0 - 59
    var s = date.getSeconds(); // 0 - 59
    var session = "";

    
    h = (h < 10) ? "0" + h : h;
    m = (m < 10) ? "0" + m : m;
    s = (s < 10) ? "0" + s : s;
    
    var time = h + ":" + m + ":" + s + " " + session;
    document.getElementById("MyClockDisplay").innerText = time;
    document.getElementById("MyClockDisplay").textContent = time;
    
    setTimeout(showTime, 1000);
    
}

showTime();