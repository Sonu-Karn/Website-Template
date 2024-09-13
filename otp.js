// async function requestOTP() {
//     const email = document.getElementById('email').value;
//     try {
//         const response = await fetch('/request_otp', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({ email: email })
//         });

//         const result = await response.json();
//         alert(result.message);
//     } catch (error) {
//         console.error('Error:', error);
//         alert('An error occurred while requesting the OTP.');
//     }
// }

// async function verifyOTP() {
//     const otp = document.getElementById('otp').value;
//     try {
//         const response = await fetch('/verify_otp', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({ otp: otp })
//         });

//         const result = await response.json();
//         alert(result.message);
//         if (result.redirect) {
//             window.location.href = result.redirect;
//         }
//     } catch (error) {
//         console.error('Error:', error);
//         alert('An error occurred while verifying the OTP.');
//     }
// }



  function sendOTP() {
    // Assuming you have a server-side endpoint to send OTP via email or SMS
    // This is a simplified example and needs server-side implementation for real-world scenarios
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;

    // Simulating sending OTP (in real-world, this would be done via AJAX/fetch to your server)
    alert(`OTP sent to ${email} or ${phone}`);

    // Show OTP verification form and hide account creation form
    document.getElementById('signup-form').style.display = 'none';
    document.getElementById('otp-form').style.display = 'block';
  }
