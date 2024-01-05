window.onload = function() {
    var delay;

    if(loginAttempts > 5 && loginAttempts <= 10) {
        delay = 30;
    } else if(loginAttempts > 10) {
        delay = 120;
    } else {
        return;
    }

    var timeLeft = delay - Math.floor((Date.now() / 1000) - lastAttemptTime);

    if(timeLeft > 0) {
        var countdown = setInterval(function() {
            if(timeLeft <= 0) {
                clearInterval(countdown);
                document.getElementById('password').disabled = false;
                document.getElementById('submit').disabled = false;
                location.reload();
            } else {
                if(loginAttempts > 5) {
                    document.getElementById('password').disabled = true;
                    document.getElementById('submit').disabled = true;
                }
                document.getElementById('countdown').innerText = "Temps restant : " + timeLeft + " secondes";
                timeLeft--;
            }
        }, 1000);
    }
}