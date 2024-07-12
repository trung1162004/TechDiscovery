<!DOCTYPE html>
<html>
<head>
    <title>Welcome to TechDiscovery </title>
    <style>
        /* Lớp mờ */
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }

        #popup {
            display: none;
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px 30px 10px 40px;
            border-radius: 7px;
            width: 600px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        #popup h1 {
            text-align: center;
            color: #444444;
            font-family: monospace;
            font-size: 35px;
        }

        #popup p {
            font-family: monospace;
            line-height: 20px;
            font-size: 15px;
        }

        #closeButton {
            background-color: red;
            color: #fff;
            padding: 6px 18px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            width: 80px;
            margin-left: 40%;
        }
    </style>
</head>
<body>
    <!-- Lớp mờ -->
    <div id="overlay">

    <div id="popup">
        <h1>Dear Customers!</h1><br>
        <p>Welcome to TechDiscovery survey site.</p>
        <p>We would like to collect your opinions on our future products.</p>
        <p>Even when you give us your opinion, you will receive a promotion code!</p>
        <p>We hope this little gift makes you happy during your purchasing.</p><br>
        <div id="closeButton">Close</div>
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popup = document.getElementById("popup");
            const overlay = document.getElementById("overlay");
            const closeButton = document.getElementById("closeButton");

            closeButton.addEventListener("click", function() {
                popup.style.display = "none";
                overlay.style.display = "none";
            });

            // Hiển thị popup và lớp mờ khi trang web tải xong
            popup.style.display = "block";
            overlay.style.display = "block";
        });
    </script>
</body>
</html>
