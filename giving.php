<?php $pageTitle = 'Online Giving | Christ Ekklesia Fellowship Chapel';
include $_SERVER['DOCUMENT_ROOT'].'/includes/header.php'; ?>

<style>
    :root {
        --primary-purple: #6b46c1;
        --secondary-purple: #7c3aed;
        --light-purple: #f3e8ff;
        --accent-gold: #f59e0b;
        --dark-purple: #553c9a;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --white: #ffffff;
        --off-white: #f8fafc;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: var(--off-white);
        color: var(--text-dark);
        line-height: 1.6;
    }

    .giving-hero {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
        color: white;
        padding: 80px 0 60px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .giving-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        opacity: 0.1;
    }

    .giving-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .giving-hero p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .giving-container {
        max-width: 800px;
        margin: -40px auto 60px auto;
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(107, 70, 193, 0.15);
        padding: 40px;
        position: relative;
        z-index: 10;
    }

    @media (max-width: 768px) {
        .giving-container {
            margin: -40px 20px 60px 20px;
            padding: 30px 20px;
        }
        
        .giving-hero h1 {
            font-size: 2.2rem;
        }
        
        .giving-hero {
            padding: 60px 0 40px 0;
        }
    }

    .church-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 50%;
        margin: 0 auto 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        box-shadow: 0 10px 30px rgba(107, 70, 193, 0.3);
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-dark);
        font-weight: 600;
        font-size: 0.95rem;
    }

    input, select {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: var(--off-white);
        font-family: inherit;
    }

    input:focus, select:focus {
        outline: none;
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 4px rgba(107, 70, 193, 0.1);
        background: white;
    }

    .amount-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    @media (min-width: 640px) {
        .amount-grid {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    .amount-btn {
        padding: 14px 8px;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-align: center;
    }

    .amount-btn:hover {
        border-color: var(--primary-purple);
        background: var(--light-purple);
        transform: translateY(-2px);
    }

    .amount-btn.active {
        background: var(--primary-purple);
        border-color: var(--primary-purple);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(107, 70, 193, 0.3);
    }

    .donate-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        border: none;
        padding: 18px 24px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 24px;
        position: relative;
        overflow: hidden;
    }

    .donate-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .donate-btn:hover::before {
        left: 100%;
    }

    .donate-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(107, 70, 193, 0.4);
    }

    .donate-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .status-message {
        margin-top: 20px;
        padding: 16px;
        border-radius: 12px;
        font-weight: 500;
        display: none;
    }

    .status-success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .status-error {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .status-info {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .loader {
        border: 3px solid rgba(255,255,255,0.3);
        border-top: 3px solid white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
        display: inline-block;
        margin-right: 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .payment-instructions {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 5px;
        margin-top: 10px;
        display: none;
    }

    .payment-instructions h3 {
        color: var(--primary-purple);
        margin-bottom: 24px;
        font-size: 1.5rem;
        text-align: center;
    }

    .donation-summary {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin: 8px 0;
        padding: 4px 0;
    }

    .summary-row:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }

    .instruction-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }

    @media (min-width: 768px) {
        .instruction-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .instruction-step {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-left: 4px solid var(--primary-purple);
        transition: all 0.3s ease;
    }

    .instruction-step:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .instruction-step strong {
        color: var(--primary-purple);
        display: block;
        margin-bottom: 8px;
    }

    .copy-field {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 12px 0;
        background: var(--light-purple);
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .copy-field span {
        flex: 1;
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-purple);
        word-break: break-all;
    }

    .copy-btn {
        background: var(--primary-purple);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 12px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .copy-btn:hover {
        background: var(--secondary-purple);
        transform: scale(1.05);
    }

    .amount-display {
        font-weight: 700;
        color: var(--primary-purple);
        font-size: 1.2rem;
        margin-top: 8px;
    }

    .new-donation-btn {
        background: var(--accent-gold);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        margin-top: 24px;
        transition: all 0.3s ease;
    }

    .new-donation-btn:hover {
        background: #d97706;
        transform: translateY(-2px);
    }

    .info-section {
        background: linear-gradient(135deg, var(--light-purple) 0%, #fef3c7 100%);
        padding: 24px;
        border-radius: 16px;
        margin-top: 32px;
        border-left: 4px solid var(--accent-gold);
    }

    .info-section h3 {
        color: var(--primary-purple);
        margin-bottom: 16px;
    }

    .info-section p {
        margin-bottom: 12px;
        line-height: 1.6;
    }

    .coming-soon-section {
        margin: 24px 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }

    .coming-soon-tab {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        padding: 16px 24px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .coming-soon-tab:hover {
        background: linear-gradient(135deg, var(--secondary-purple), var(--primary-purple));
    }

    .coming-soon-badge {
        background: var(--accent-gold);
        color: var(--text-dark);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .coming-soon-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        background: var(--off-white);
    }

    .coming-soon-content.show {
        max-height: 300px;
        padding: 24px;
    }

    .coming-soon-content ul {
        padding-left: 20px;
        margin: 16px 0;
    }

    .coming-soon-content li {
        margin-bottom: 8px;
        color: var(--text-light);
    }

    .coming-soon-content p {
        color: var(--text-light);
        line-height: 1.6;
    }
</style>

<div class="giving-hero">
    <div class="container">
        <h1 style="color:white">Support Our Ministry</h1>
        <p style="color:white">Your generous giving helps us fulfill our mission to celebrate the supremacy of Jesus Christ in all things and serve our community with love.</p>
    </div>
</div>

<div class="container">
    <div class="giving-container">
        
        
        <form id="givingForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="254XXXXXXXXX" required>
                </div>

                <div class="form-group">
                    <label for="giving-type">Giving Type</label>
                    <select id="giving-type" name="giving-type" required>
                        <option value="">Select giving type</option>
                        <option value="tithe">Tithe</option>
                        <option value="offering">Offering</option>
                        <option value="project">Project</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Quick Amount Selection</label>
                <div class="amount-grid">
                    <button type="button" class="amount-btn" data-amount="100">KSh 100</button>
                    <button type="button" class="amount-btn" data-amount="500">KSh 500</button>
                    <button type="button" class="amount-btn" data-amount="1000">KSh 1,000</button>
                    <button type="button" class="amount-btn" data-amount="2000">KSh 2,000</button>
                    <button type="button" class="amount-btn" data-amount="5000">KSh 5,000</button>
                    <button type="button" class="amount-btn" data-amount="other">Other</button>
                </div>
            </div>

            <div class="form-group">
                <label for="amount">Amount (KSh)</label>
                <input type="number" id="amount" name="amount" min="1" placeholder="Enter amount" required>
            </div>

            <div class="form-group">
                <label for="message">Message (Optional)</label>
                <input type="text" id="message" name="message" placeholder="Prayer request or message">
            </div>

            <button type="submit" class="donate-btn" id="donateBtn">
                Generate Payment Instructions
            </button>
        </form>

        <div id="statusMessage" class="status-message"></div>

        <div id="paymentInstructions" class="payment-instructions">
            <h3>📱 M-Pesa Payment Instructions</h3>
            
            <div class="donation-summary">
                <div class="summary-row">
                    <span><strong>Phone Number:</strong></span>
                    <span id="summaryPhone"></span>
                </div>
                <div class="summary-row">
                    <span><strong>Giving Type:</strong></span>
                    <span id="summaryType"></span>
                </div>
                <div class="summary-row">
                    <span><strong>Amount:</strong></span>
                    <span id="summaryAmount"></span>
                </div>
            </div>

            <div class="instruction-grid">
                <div class="instruction-step">
                    <strong>Step 1:</strong> Open M-Pesa on your phone
                </div>
                
                <div class="instruction-step">
                    <strong>Step 2:</strong> Select "Lipa na M-Pesa" → "Pay Bill"
                </div>
                
                <div class="instruction-step">
                    <strong>Step 3:</strong> Enter Business Number:
                    <div class="copy-field">
                        <span id="businessNumber">116519</span>
                        <button class="copy-btn" onclick="copyText('businessNumber')">
                            📋 Copy
                        </button>
                    </div>
                </div>
                
                <div class="instruction-step">
                    <strong>Step 4:</strong> Enter Account Number:
                    <div class="copy-field">
                        <span id="accountNumber"></span>
                        <button class="copy-btn" onclick="copyText('accountNumber')">
                            📋 Copy
                        </button>
                    </div>
                </div>
                
                <div class="instruction-step">
                    <strong>Step 5:</strong> Enter Amount:
                    <div class="amount-display" id="paymentAmount"></div>
                </div>
                
                <div class="instruction-step">
                    <strong>Step 6:</strong> Enter your M-Pesa PIN to complete
                </div>
            </div>

            <button class="new-donation-btn" onclick="resetForm()">💝 Make Another Donation</button>
        </div>

        <div class="info-section">
            <h3>💡 How to Complete Your Donation</h3>
            <p><strong>Step 1:</strong> Fill in your details and click "Generate Payment Instructions"</p>
            <p><strong>Step 2:</strong> Go to M-Pesa menu → Lipa na M-Pesa → Pay Bill</p>
            <p><strong>Step 3:</strong> Enter Business Number: <strong>116519</strong></p>
            <p><strong>Step 4:</strong> Enter the Account Number as shown in the instructions</p>
            <p><strong>Step 5:</strong> Enter the amount and your M-Pesa PIN</p>
        </div>

        <div class="coming-soon-section">
            <div class="coming-soon-tab" onclick="toggleComingSoon('automatic')">
                <span>🔄 Set Up Automatic Giving</span>
                <span class="coming-soon-badge">Coming Soon</span>
            </div>
            <div id="automatic-info" class="coming-soon-content">
                <p>We're working on making it easier for you to support our ministry through automatic recurring donations. This feature will allow you to:</p>
                <ul>
                    <li>Set up weekly, bi-weekly, or monthly donations</li>
                    <li>Choose your preferred payment method</li>
                    <li>Update or cancel anytime</li>
                </ul>
                <p>For now, please use the manual donation option above. Thank you for your support!</p>
            </div>
        </div>

        <div class="coming-soon-section">
            <div class="coming-soon-tab" onclick="toggleComingSoon('stk')">
                <span>⚡ STK Push Payment</span>
                <span class="coming-soon-badge">Coming Soon</span>
            </div>
            <div id="stk-info" class="coming-soon-content">
                <p>Our STK Push payment option is coming soon! This will allow you to:</p>
                <ul>
                    <li>Make instant donations with just a tap</li>
                    <li>Receive payment confirmation directly on your phone</li>
                    <li>Get instant donation receipts</li>
                </ul>
                <p>Until then, please use the Paybill option above. We appreciate your patience!</p>
            </div>
        </div>
    </div>
</div>

<script>
// Configuration
const CONFIG = {
    paybill: '116519',
    accountPrefix: '#006255#',
    recordEndpoint: '/api/donations/record',
    notificationEndpoint: '/api/notifications/send'
};

// DOM elements
const form = document.getElementById('givingForm');
const phoneInput = document.getElementById('phone');
const amountInput = document.getElementById('amount');
const donateBtn = document.getElementById('donateBtn');
const statusMessage = document.getElementById('statusMessage');
const amountButtons = document.querySelectorAll('.amount-btn');

// Toggle coming soon sections
function toggleComingSoon(section) {
    const content = document.getElementById(`${section}-info`);
    content.classList.toggle('show');
}

// Format phone number
function formatPhoneNumber(phone) {
    phone = phone.replace(/\D/g, '');
    if (phone.startsWith('0')) {
        phone = '254' + phone.substring(1);
    } else if (phone.startsWith('7') || phone.startsWith('1')) {
        phone = '254' + phone;
    }
    return phone;
}

// Validate phone number
function isValidPhoneNumber(phone) {
    const formatted = formatPhoneNumber(phone);
    return /^254[71]\d{8}$/.test(formatted);
}

// Amount button handlers
amountButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        amountButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        if (btn.dataset.amount === 'other') {
            amountInput.value = '';
            amountInput.focus();
        } else {
            amountInput.value = btn.dataset.amount;
        }
    });
});

// Show status message
function showStatus(message, type) {
    statusMessage.className = `status-message status-${type}`;
    statusMessage.innerHTML = message;
    statusMessage.style.display = 'block';
    
    if (type === 'success') {
        setTimeout(() => {
            statusMessage.style.display = 'none';
        }, 10000);
    }
}

// Generate unique account number
function generateAccountNumber(phone, givingType) {
    const timestamp = Date.now().toString().slice(-6);
    return `${CONFIG.accountPrefix}${givingType.toUpperCase().replace(/-/g, '')}`;
}

// Copy text to clipboard
function copyText(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent || element.innerText;
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showStatus('✅ Copied to clipboard!', 'success');
        });
    } else {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showStatus('✅ Copied to clipboard!', 'success');
    }
}

// Reset form
function resetForm() {
    form.reset();
    amountButtons.forEach(b => b.classList.remove('active'));
    document.getElementById('paymentInstructions').style.display = 'none';
    statusMessage.style.display = 'none';
}

// Record donation (optional)
async function recordDonation(donationData) {
    try {
        if (CONFIG.recordEndpoint) {
            await fetch(CONFIG.recordEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(donationData)
            });
        }
    } catch (error) {
        console.log('Could not record donation:', error);
    }
}

// Toggle donate button
function toggleDonateButton(disabled, text = 'Generate Payment Instructions') {
    donateBtn.disabled = disabled;
    donateBtn.innerHTML = disabled ? 
        '<div class="loader"></div>' + text : 
        text;
}

// Form submission
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(form);
    const phone = formData.get('phone');
    const amount = parseFloat(formData.get('amount'));
    const givingType = formData.get('giving-type');
    const message = formData.get('message') || '';

    // Validation
    if (!isValidPhoneNumber(phone)) {
        showStatus('❌ Please enter a valid M-Pesa phone number (07XXXXXXXX or 01XXXXXXXX)', 'error');
        return;
    }

    if (!amount || amount < 1) {
        showStatus('❌ Please enter a valid amount', 'error');
        return;
    }

    if (!givingType) {
        showStatus('❌ Please select a giving type', 'error');
        return;
    }

    // Show loading
    toggleDonateButton(true, 'Generating Instructions...');

    // Generate account number
    const accountNumber = generateAccountNumber(formatPhoneNumber(phone), givingType);
    
    // Prepare donation data
    const donationData = {
        phoneNumber: formatPhoneNumber(phone),
        amount: Math.round(amount),
        givingType: givingType,
        message: message,
        accountNumber: accountNumber,
        timestamp: new Date().toISOString(),
        paybill: CONFIG.paybill
    };

    // Record donation attempt
    await recordDonation(donationData);

    // Update display
    document.getElementById('summaryPhone').textContent = phone;
    document.getElementById('summaryType').textContent = givingType.replace('-', ' ').toUpperCase();
    document.getElementById('summaryAmount').textContent = `KSh ${amount.toLocaleString()}`;
    document.getElementById('businessNumber').textContent = CONFIG.paybill;
    document.getElementById('accountNumber').textContent = accountNumber;
    document.getElementById('paymentAmount').textContent = `KSh ${amount.toLocaleString()}`;

    // Show instructions
    document.getElementById('paymentInstructions').style.display = 'block';
    
    showStatus('✅ Payment instructions generated! Please follow the M-Pesa steps below.', 'success');

    // Scroll to instructions
    document.getElementById('paymentInstructions').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });

    toggleDonateButton(false);
});

// Phone formatting
phoneInput.addEventListener('input', (e) => {
    let value = e.target.value.replace(/\D/g, '');
    
    if (value.startsWith('254')) {
        e.target.value = value;
    } else if (value.startsWith('0')) {
        if (value.length > 10) value = value.substring(0, 10);
        e.target.value = value;
    } else if (value.startsWith('7') || value.startsWith('1')) {
        e.target.value = '0' + value;
    }
});

// Amount input handling
amountInput.addEventListener('input', () => {
    amountButtons.forEach(b => b.classList.remove('active'));
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    console.log('Christ Ekklesia Giving Page Loaded');
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'; ?>