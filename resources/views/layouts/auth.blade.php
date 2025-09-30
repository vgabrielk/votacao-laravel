<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Firebase SDK -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "AIzaSyDAy2rztUpNJViXucmqEYLRu73obHv_FdY",
            authDomain: "laravel-70567.firebaseapp.com",
            projectId: "laravel-70567",
            storageBucket: "laravel-70567.firebasestorage.app",
            messagingSenderId: "242245514937",
            appId: "1:242245514937:web:36102d2b1f688db12ecd68"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        // Google Sign In Function
        window.googleSignIn = async function() {
            try {
                const result = await signInWithPopup(auth, provider);
                const user = result.user;
                
                // Send user data to Laravel backend
                const response = await fetch('/auth/google', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        uid: user.uid,
                        email: user.email,
                        name: user.displayName,
                        photo: user.photoURL
                    })
                });

                if (response.ok) {
                    window.location.href = '/dashboard';
                } else {
                    console.error('Authentication failed');
                }
            } catch (error) {
                console.error('Google Sign In Error:', error);
            }
        };
    </script>
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0;
            padding: 0;
        }
        
        .night-sky {
            background: linear-gradient(180deg, #0f172a 0%, #1e3a8a 100%);
            position: relative;
            overflow: hidden;
        }
        
        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, #fff, transparent),
                radial-gradient(2px 2px at 40px 70px, #fff, transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(1px 1px at 130px 80px, #fff, transparent),
                radial-gradient(2px 2px at 160px 30px, #fff, transparent);
            background-repeat: repeat;
            background-size: 200px 100px;
            animation: twinkle 3s ease-in-out infinite alternate;
        }
        
        @keyframes twinkle {
            0% { opacity: 0.8; }
            100% { opacity: 1; }
        }
        
        .moon {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%);
            border-radius: 50%;
            box-shadow: 
                inset -20px -20px 0 0 #81d4fa,
                inset -40px -40px 0 0 #4fc3f7,
                0 0 50px rgba(255, 255, 255, 0.3);
        }
        
        .moon::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 30px;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
        }
        
        .moon::after {
            content: '';
            position: absolute;
            top: 40px;
            left: 60px;
            width: 15px;
            height: 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
        }
        
        .mountains {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(180deg, transparent 0%, #0f172a 100%);
        }
        
        .mountain {
            position: absolute;
            bottom: 0;
            width: 0;
            height: 0;
        }
        
        .mountain:nth-child(1) {
            left: 0;
            border-left: 200px solid transparent;
            border-bottom: 150px solid #0f172a;
        }
        
        .mountain:nth-child(2) {
            left: 150px;
            border-left: 300px solid transparent;
            border-bottom: 180px solid #1e293b;
        }
        
        .mountain:nth-child(3) {
            right: 0;
            border-right: 250px solid transparent;
            border-bottom: 160px solid #0f172a;
        }
        
        .mountain-reflection {
            position: absolute;
            bottom: -150px;
            left: 0;
            width: 100%;
            height: 150px;
            background: linear-gradient(0deg, rgba(15, 23, 42, 0.3) 0%, transparent 100%);
            transform: scaleY(-1);
        }
        
        .social-btn {
            transition: all 0.3s ease;
        }
        
        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .form-input {
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        
        .login-btn {
            transition: all 0.3s ease;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        .pagination-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .dot.active {
            width: 24px;
            height: 8px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body class="min-h-screen">
    @yield('content')
</body>
</html>
