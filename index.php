header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
<?php
// This is the server-side PHP code
$title = "";
$content = "";
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>سعر الشحن</title>
  <link href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #0f1523;
      --secondary-color: #0f1523;
      --accent-color: #e1cbbd;
      --error-color: #f14668;
      --text-color: #ffffff;
      --shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
      --radius: 20px;
      --transition: all 0.3s ease-in-out;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Cairo', sans-serif;
    }

    body {
      background: #0f1523;
      color: var(--text-color);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }

    .calculator-container {
      background-color: rgba(29, 35, 57, 0.5);
      padding: 40px 30px;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      max-width: 450px;
      width: 100%;
      text-align: center;
      animation: fadeIn 0.8s ease-out;
    }

    h1 {
      font-size: 2.5rem;
      margin-bottom: 24px;
      color: #e1cbbd;
    }

    .form-group {
      margin-bottom: 24px;
      text-align: right;
    }

    label {
      display: block;
      font-size: 1rem;
      margin-bottom: 8px;
      color: var(--text-color);
    }

    input {
      width: 100%;
      padding: 12px;
      border: px solid var(--primary-color);
      border-radius: var(--radius);
      background: rgba(32, 33, 36, 0.5);
      color: var(--text-color);
      font-size: 1rem;
      text-align: right;
      transition: var(--transition);
    }

    input:focus {
      outline: none;
      border-color: var(--accent-color);
      box-shadow: 0 0 8px var(--accent-color);
    }

    .dimensions-group {
      display: flex;
      gap: 10px;
    }

    .dimensions-group input {
      flex: 1;
    }

    button {
      background-color: rgba(32, 33, 36, 0.5);
      color: #fff;
      padding: 14px 20px;
      border: none;
      border-radius: var(--radius);
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      transition: var(--transition);
      width: 100%;
      margin-top: 20px;
    }

    button:hover {
      background-color: #e1cbbd;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .result,
    .error {
      margin-top: 20px;
      padding: 20px;
      border-radius: var(--radius);
      text-align: center;
      display: none;
    }

    .result {
      background-color: rgba(29, 35, 57, 0.5);
      border: px solid var(--accent-color);
      color: var(--accent-color);
      animation: slideIn 0.5s ease-out;
    }

    .result h3 {
    Color: #fff;
      margin-bottom: 10px;
    }

    .error {
      background-color: var(--error-color);
      border: 2px solid darkred;
      color: #fff;
      animation: shake 0.5s ease-out;
    }

    footer {
      margin-top: 20px;
      font-size: 0.8rem;
      text-align: center;
    }

    footer a {
      color: var(--accent-color);
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes shake {
      0%, 100% {
        transform: translateX(0);
      }
      25% {
        transform: translateX(-5px);
      }
      50% {
        transform: translateX(5px);
      }
      75% {
        transform: translateX(-5px);
      }
    }
  </style>
</head>
<body>
  <div class="calculator-container">
    <h1>(⌒~⌒)</h1>
    <form id="shippingForm">
      <div class="form-group">
        <label for="weight">الوزن (كغم):</label>
        <input type="number" id="weight" min="0.1" step="0.1" placeholder="أدخل وزن الطرد" required>
      </div>
      <div class="form-group">
        <label for="dimensions">الابعاد (سم):</label>
        <div class="dimensions-group">
          <input type="number" id="length" placeholder="الطول" required>
          <input type="number" id="width" placeholder="العرض" required>
          <input type="number" id="height" placeholder="الارتفاع" required>
        </div>
      </div>
      <button type="button" onclick="calculateShipping()">حساب التكلفة</button>
    </form>
    <div id="error" class="error"></div>
    <div id="result" class="result"></div>      
    <footer>
      <small>*مو كل الاسعار صحيحة وثابتة، بعض المرات قد يطلع ارقام عشوائية او غير مفهومة*</br> </small>
    </footer>
  </div>

  <script>
    const RATES = {
      standard: 5000,
      minimum: 5000,
      handling: 2000,
    };

    function calculateShipping() {
      const weight = parseFloat(document.getElementById('weight').value);
      const length = parseFloat(document.getElementById('length').value);
      const width = parseFloat(document.getElementById('width').value);
      const height = parseFloat(document.getElementById('height').value);

      const resultDiv = document.getElementById('result');
      const errorDiv = document.getElementById('error');

      resultDiv.style.display = 'none';
      errorDiv.style.display = 'none';

      if (isNaN(weight) || weight <= 0) {
        showError("الرجاء إدخال وزن صحيح أكبر من صفر");
        return;
      }

      if (isNaN(length) || length <= 0 || isNaN(width) || width <= 0 || isNaN(height) || height <= 0) {
        showError(" error");
        return;
      }


      const volumetricWeight = (length * width * height) / 25;
      const chargeableWeight = Math.max(weight, volumetricWeight);
      const baseCost = Math.max(chargeableWeight * RATES.standard, RATES.minimum);
      const totalCost = baseCost + RATES.handling;

      resultDiv.innerHTML = `
        <h3>التكلفة الإجمالية: ${formatCurrency(totalCost)} د.ع</h3>
        <h3>رسوم الشحن: ${formatCurrency(baseCost)} د.ع</h3>
        <h3>رسوم اخرى : ${formatCurrency(RATES.handling)} د.ع</h3>
      `;
      resultDiv.style.display = 'block';
    }

    function showError(message) {
      const errorDiv = document.getElementById('error');
      errorDiv.textContent = message;
      errorDiv.style.display = 'block';
    }
    

    function formatCurrency(amount) {
      return amount.toLocaleString('ar-IQ');
    }
  </script>
</body>
</html>