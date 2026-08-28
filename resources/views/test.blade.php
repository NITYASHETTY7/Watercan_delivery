<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Icon Cycler Fix</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Ensures the icon container defines the space */
        .icon-cycle {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Ensures icon size is manageable if not set by font-size */
            overflow: hidden;
        }

        /* Essential styles for layering and fading */
        .icon-cycle .delivery-icon {
            position: absolute;
            /* Crucial: Layers the icons exactly on top of each other */
            opacity: 0;
            /* Start hidden (except for the one with 'active') */
            transition: opacity 1s ease-in-out;
            /* Crucial: Defines the smooth fade duration */
            font-size: 1.5rem;
            /* Ensure the icons have a visible size */
        }

        /* The class that makes an icon visible */
        .icon-cycle .delivery-icon.active {
            opacity: 1;
        }
    </style>
</head>

<body class="p-5">

    <div
        class="w-16 h-16 min-w-16 flex items-center justify-center 
            rounded-full bg-white shadow-lg border border-blue-100">

        <div class="icon-cycle">

            <i class="fas fa-magnifying-glass text-blue-600 delivery-icon active"></i>

            <i class="fas fa-truck-fast text-blue-600 delivery-icon"></i>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const iconCycleContainer = document.querySelector('.icon-cycle');

            // Corrected querySelector: uses the CSS class name 'delivery-icon'
            const icons = iconCycleContainer ? iconCycleContainer.querySelectorAll('.delivery-icon') : [];

            if (icons.length < 2) {
                console.error("Not enough icons found to start the cycle.");
                return;
            }

            let currentIndex = 0; // Starts at 0, which is the 'active' magnifying glass

            function cycleIcons() {
                // 1. Fade out the current icon (removes opacity: 1)
                icons[currentIndex].classList.remove('active');

                // 2. Move to the next index
                currentIndex = (currentIndex + 1) % icons.length;

                // 3. Fade in the next icon (adds opacity: 1)
                icons[currentIndex].classList.add('active');
            }

            // Interval: 3000ms (3 seconds) is correct for a 1s transition + 2s display time
            const intervalTime = 2000;
            setInterval(cycleIcons, intervalTime);
        });
    </script>

</body>

</html>
