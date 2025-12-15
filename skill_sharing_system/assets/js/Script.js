// assets/js/script.js
// small helpers (future: add AJAX, validations)
/*
console.log("SkillShare: script loaded");

// Disable options styling
document.addEventListener("DOMContentLoaded", () => {
  const select = document.querySelector('select[name="skill_choice"]');
  if (!select) return;
  Array.from(select.options).forEach((opt) => {
    if (opt.disabled) {
      opt.style.color = "#999";
    }
  });
});

// Example: simple alert on button click
document.addEventListener("DOMContentLoaded", function () {
  const alertButtons = document.querySelectorAll(".alert-button");
  alertButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      alert("Button clicked!");
    });
  });
});


<form onsubmit="document.getElementById('btn-submit').disabled = true; ...">
  <button id="btn-submit">Request</button>
</form>;

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Staggered Card Animation
    // Finds all .card elements and fades them in one by one
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('reveal');
        }, 150 * (index + 1)); // 150ms delay between each card
    });

    // 2. Button Ripple Effect (Micro-interaction)
    // Adds a subtle click scale effect to all buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mousedown', () => {
            btn.style.transform = 'scale(0.95)';
        });
        btn.addEventListener('mouseup', () => {
            btn.style.transform = 'scale(1)';
        });
        // Reset if mouse leaves
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'scale(1)';
        });
    });

    // 3. Auto-Dismiss Flash Messages
    // If a success/error message exists, remove it after 4 seconds
    const flashMsg = document.querySelector('.flash');
    if (flashMsg) {
        setTimeout(() => {
            flashMsg.style.transition = 'opacity 0.5s ease';
            flashMsg.style.opacity = '0';
            setTimeout(() => flashMsg.remove(), 500);
        }, 4000);
    }
});

*/


document.addEventListener('DOMContentLoaded', () => {
    
    // 1. STAGGERED REVEAL ANIMATION
    // This looks for any element with class .card and fades them in one by one.
    const cards = document.querySelectorAll('.card');
    
    if (cards.length > 0) {
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('visible');
            }, 100 * (index + 1)); // 100ms delay per card
        });
    }

    // 2. INPUT FOCUS ANIMATION (Glow Effect)
    // Adds a subtle parent-glow if needed, but CSS handles most of this.
    // We can use this to clear validation errors when user types.
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            if(input.classList.contains('error')) {
                input.classList.remove('error');
            }
        });
    });

    // 3. TABLE ROW CLICKABLE (Optional UX improvement)
    // If a row has a primary action, clicking the row triggers it.
    // (You can enable this if you want rows to act like links)
    /*
    const rows = document.querySelectorAll('.table tr[data-href]');
    rows.forEach(row => {
        row.addEventListener('click', () => {
            window.location.href = row.dataset.href;
        });
    });
    */
});




