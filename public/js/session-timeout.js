/**
 * Session Timeout Warning
 * Warns users 5 minutes before their session expires
 */

document.addEventListener('DOMContentLoaded', function() {
    const SESSION_LIFETIME = 60 * 60 * 1000; // 60 minutes in milliseconds
    const WARNING_TIME = 5 * 60 * 1000; // 5 minutes in milliseconds
    const TIMEOUT_DURATION = SESSION_LIFETIME - WARNING_TIME;

    let inactivityTimeout;
    let warningTimeout;
    let isWarningShown = false;

    // Reset timers on user activity
    function resetTimers() {
        clearTimeout(inactivityTimeout);
        clearTimeout(warningTimeout);
        isWarningShown = false;

        if (document.getElementById('session-warning-modal')) {
            document.getElementById('session-warning-modal').style.display = 'none';
        }

        // Set new warning timeout
        warningTimeout = setTimeout(showWarning, TIMEOUT_DURATION);
    }

    // Show session expiration warning
    function showWarning() {
        if (!isWarningShown) {
            isWarningShown = true;
            
            // Create modal if not exists
            if (!document.getElementById('session-warning-modal')) {
                createWarningModal();
            }
            
            const modal = document.getElementById('session-warning-modal');
            modal.style.display = 'flex';
            
            // Start countdown
            startCountdown();
            
            // Auto logout after 5 minutes
            inactivityTimeout = setTimeout(logoutSession, WARNING_TIME);
        }
    }

    // Create warning modal
    function createWarningModal() {
        const modal = document.createElement('div');
        modal.id = 'session-warning-modal';
        modal.style.cssText = `
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        `;

        modal.innerHTML = `
            <div style="background: white; padding: 30px; border-radius: 8px; max-width: 400px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); text-align: center;">
                <h3 style="margin: 0 0 15px 0; color: #ef4444; font-size: 18px; font-weight: 600;">
                    ⚠️ Peringatan: Sesi Akan Segera Berakhir
                </h3>
                <p style="margin: 15px 0; color: #666; font-size: 14px;">
                    Sesi Anda akan berakhir dalam <strong id="countdown-timer">5:00</strong> menit akibat tidak ada aktivitas.
                </p>
                <p style="margin: 15px 0; color: #666; font-size: 13px;">
                    Klik tombol di bawah untuk melanjutkan sesi Anda.
                </p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <button id="session-continue-btn" onclick="continueSession()" style="padding: 10px 15px; background-color: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">
                        Lanjutkan
                    </button>
                    <button id="session-logout-btn" onclick="logoutSession()" style="padding: 10px 15px; background-color: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">
                        Logout
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
    }

    // Start countdown timer
    function startCountdown() {
        let timeLeft = 5 * 60; // 5 minutes in seconds
        const timerElement = document.getElementById('countdown-timer');

        const countdownInterval = setInterval(() => {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            if (timerElement) {
                timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }

    // Continue session (requires user to have activity)
    window.continueSession = function() {
        // Make a request to update session
        fetch(window.location.href, {
            method: 'HEAD',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(() => {
            resetTimers();
            const modal = document.getElementById('session-warning-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    };

    // Logout session
    window.logoutSession = function() {
        // Redirect to logout
        window.location.href = '/logout';
    };

    // Track user activity
    const events = ['mousedown', 'keydown', 'scroll', 'touchstart', 'click'];
    
    events.forEach(event => {
        document.addEventListener(event, resetTimers, true);
    });

    // Initialize timers on page load
    resetTimers();
});
