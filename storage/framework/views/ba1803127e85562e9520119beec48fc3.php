
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title>Member Registration</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  background: #05080f;
  font-family: 'DM Sans', sans-serif;
  color: #fff;
  padding: 40px 16px;
  min-height: 100vh;
}
body.modal-open { overflow: hidden; }

.container {
  max-width: 480px;
  margin: 0 auto;
  background: #0d1425;
  padding: 32px 28px 28px;
  border-radius: 14px;
  box-shadow: 0 0 40px rgba(26,111,255,.18);
}
h2 { text-align: center; margin: 0 0 24px; font-size: 1.4rem; color: #fff; font-weight: 700; }

/* ── labels ── */
label.field-label {
  display: block; font-size: .8rem; color: #8ba3c4;
  margin-bottom: 4px; font-weight: 600; letter-spacing: .4px;
}
.field-wrap { margin-bottom: 14px; }

/* ── inputs ── */
input[type=text],
input[type=email],
input[type=tel],
select,
textarea {
  width: 100%; padding: 11px 14px;
  background: #111c35; border: 1px solid #1e2e50;
  color: #fff; border-radius: 8px; font-size: .93rem;
  outline: none; transition: border .2s; font-family: inherit;
}
input:focus, select:focus, textarea:focus { border-color: #1a6fff; }
input.is-error, select.is-error { border-color: #ff4c4c !important; }
input.is-ok { border-color: #00c853 !important; }
select option { background: #0d1425; }
textarea { resize: vertical; min-height: 60px; }

/* ── error / hint text ── */
.err-msg {
  display: none; font-size: .76rem;
  color: #ff5c5c; margin-top: 4px;
}
.err-msg.show { display: block; }
.hint-msg {
  display: none; font-size: .76rem;
  color: #8ba3c4; margin-top: 4px;
}

/* ── phone row ── */
.phone-row { display: flex; gap: 8px; }
.phone-row select { width: 165px; flex-shrink: 0; }
.phone-row input  { flex: 1; }
.phone-hint {
  font-size: .75rem; color: #8ba3c4;
  margin-top: 4px; display: block;
}
.phone-hint.error { color: #ff5c5c; }
.phone-hint.ok    { color: #00c853; }

/* ── sponsor section ── */
#sponsorInputWrap { display: none; }

/* ── admin sponsor card (shown for no_sponsor) ── */
#adminSponsorCard {
  display: none;
  background: #111c35;
  border: 1px solid #1a6fff44;
  border-radius: 8px;
  padding: 11px 14px;
  margin-top: 8px;
  font-size: .87rem;
  line-height: 1.9;
}
#adminSponsorCard b   { color: #8ba3c4; }
#adminSponsorCard span { color: #fff; }
#adminSponsorCard .sc-mid { color: #1a6fff !important; font-weight: 700; }

.sponsor-input-row {
  position: relative;
}
.sponsor-input-row input {
  padding-right: 42px;
}
.sponsor-spinner {
  position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
  width: 18px; height: 18px; display: none;
  border: 2px solid #1e2e50; border-top-color: #1a6fff;
  border-radius: 50%; animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }

/* ── sponsor autocomplete dropdown ── */
#sponsorDropdown {
  display: none;
  position: absolute;
  z-index: 999;
  width: 100%;
  background: #111c35;
  border: 1px solid #1a6fff55;
  border-top: none;
  border-radius: 0 0 8px 8px;
  max-height: 260px;
  overflow-y: auto;
  box-shadow: 0 8px 24px rgba(0,0,0,.5);
  top: 100%;
  left: 0;
}
.sp-item {
  padding: 10px 14px;
  cursor: pointer;
  border-bottom: 1px solid #1e2e50;
  transition: background .15s;
}
.sp-item:last-child { border-bottom: none; }
.sp-item:hover, .sp-item.sp-active { background: #1a3a6a; }
.sp-item .sp-name  { color: #fff; font-weight: 600; font-size: .88rem; }
.sp-item .sp-meta  { color: #8ba3c4; font-size: .76rem; margin-top: 2px; }
.sp-item .sp-id    { color: #1a6fff; font-weight: 700; }
.sp-no-result      { padding: 12px 14px; color: #ff5c5c; font-size: .84rem; }
mark.sp-hl {
  background: #1a6fff33;
  color: #7ab4ff;
  border-radius: 2px;
}

#sponsorCard {
  display: none; background: #111c35;
  border: 1px solid #1a6fff44; border-radius: 8px;
  padding: 11px 14px; margin-top: 8px;
  font-size: .87rem; line-height: 1.9;
}
#sponsorCard.error-card { border-color: #ff4c4c44; }
#sponsorCard b   { color: #8ba3c4; }
#sponsorCard span { color: #fff; }
.sc-mid { color: #1a6fff !important; font-weight: 700; }
.sc-not-found { color: #ff5c5c; font-size: .84rem; }

/* Hidden — kept in DOM so JS references don't break */
#clearSponsorBtn { display: none !important; visibility: hidden; position: absolute; }

/* ── terms ── */
.terms-row {
  display: flex; align-items: flex-start; gap: 10px;
  margin: 18px 0 20px; font-size: .83rem;
  color: #8ba3c4; line-height: 1.5;
  background: #111c35; border: 1px solid #1e2e50;
  border-radius: 8px; padding: 12px 14px;
}
.terms-row.is-error { border-color: #ff4c4c; }
.terms-row input[type=checkbox] {
  width: 17px; height: 17px; accent-color: #1a6fff;
  flex-shrink: 0; margin-top: 2px; cursor: pointer;
}
.terms-row a { color: #1a6fff; text-decoration: underline; cursor: pointer; }

/* ── submit btn ── */
button[type=submit] {
  width: 100%; padding: 13px;
  background: linear-gradient(135deg, #1a6fff, #0f4ec7);
  border: none; border-radius: 8px; color: #fff;
  font-size: 1rem; font-weight: 700; cursor: pointer;
  transition: opacity .2s, transform .1s; letter-spacing: .3px;
}
button[type=submit]:hover:not(:disabled) { opacity: .9; transform: translateY(-1px); }
button[type=submit]:disabled { background: #1a3a7a; cursor: not-allowed; opacity: .5; }

/* ════════════ MODAL BASE ════════════ */
.modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.83); z-index: 9999;
  overflow-y: auto; padding: 24px 16px;
}
.modal-overlay.active { display: block; }

.modal-box {
  background: #0d1425; border: 1px solid #1e2e50;
  border-radius: 14px; width: 100%; max-width: 480px;
  margin: 0 auto; animation: slideUp .25s ease;
}
@keyframes slideUp {
  from { transform: translateY(22px); opacity: 0; }
  to   { transform: translateY(0);    opacity: 1; }
}

.modal-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 22px 16px; border-bottom: 1px solid #1e2e50;
  border-radius: 14px 14px 0 0; background: #0d1425;
}
.modal-header h3 { font-size: 1.05rem; color: #fff; margin: 0; font-weight: 700; }
.modal-close-x {
  background: #111c35; border: 1px solid #1e2e50;
  color: #8ba3c4; width: 32px; height: 32px;
  border-radius: 8px; cursor: pointer; font-size: 1rem;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s; flex-shrink: 0;
}
.modal-close-x:hover { background: #1e2e50; color: #fff; }

.modal-body { padding: 20px 22px 24px; }
.modal-body p { color: #cdd5e0; font-size: .88rem; line-height: 1.7; margin-bottom: 12px; }
.modal-body p strong { color: #fff; }
.modal-body em { color: #f0a500; }
.modal-close-btn {
  display: block; width: 100%; margin-top: 6px;
  padding: 11px; background: #1a6fff; color: #fff;
  border: none; border-radius: 8px; cursor: pointer;
  font-size: .95rem; font-weight: 700;
}
.modal-close-btn:hover { background: #1558cc; }

/* ════════════ PAYMENT MODAL ════════════ */
.badge {
  display: inline-flex; align-items: center; gap: 5px;
  background: #f0a50022; color: #f0a500;
  border: 1px solid #f0a50044; padding: 3px 10px;
  border-radius: 20px; font-size: .78rem; font-weight: 600; margin-bottom: 14px;
}

.member-summary {
  background: #111c35; border: 1px solid #1e2e50;
  border-radius: 10px; padding: 4px 16px; margin-bottom: 16px;
}
.member-summary .mrow {
  display: flex; justify-content: space-between; align-items: center;
  padding: 9px 0; border-bottom: 1px solid #1a2540;
}
.member-summary .mrow:last-child { border-bottom: none; }
.member-summary .lbl { color: #8ba3c4; font-weight: 600; font-size: .79rem; white-space: nowrap; }
.member-summary .val { color: #fff; font-weight: 600; text-align: right; word-break: break-all; max-width: 60%; }
.member-summary .val.mid  { color: #1a6fff; font-size: 1rem; font-weight: 700; }
.member-summary .val.pass { color: #f5a623; font-weight: 700; font-family: monospace; font-size: .93rem; }
.member-summary .val.txn  { color: #a78bfa; font-weight: 700; font-family: monospace; font-size: .93rem; }
.member-summary .val.pend { color: #f0a500; }
.member-summary .val.amt  { color: #00c853; font-weight: 700; font-size: 1rem; }

.info-note {
  background: #1a6fff11; border: 1px solid #1a6fff33;
  border-radius: 8px; padding: 10px 14px;
  font-size: .82rem; color: #8ba3c4; line-height: 1.6;
  margin-bottom: 16px; display: flex; gap: 8px;
}
.info-note i { color: #1a6fff; flex-shrink: 0; margin-top: 2px; }

.section-divider {
  display: flex; align-items: center; gap: 10px;
  margin: 16px 0 14px; font-size: .72rem; color: #1a6fff;
  font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
}
.section-divider::before, .section-divider::after {
  content: ''; flex: 1; height: 1px; background: #1e2e50;
}

.payment-qr { text-align: center; margin-bottom: 16px; }
.payment-qr img {
  width: 188px; height: 188px; border-radius: 10px;
  border: 2px solid #1a6fff55; object-fit: cover;
}
.payment-qr p { margin-top: 7px; font-size: .77rem; color: #8ba3c4; }

.upi-badge {
  display: flex; align-items: center; justify-content: space-between;
  background: #111c35; border: 1px solid #1e2e50;
  border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; gap: 8px;
}
.upi-badge small { color: #8ba3c4; font-size: .72rem; display: block; }
.upi-badge span  { color: #fff; font-size: .9rem; font-weight: 600; }
.copy-btn {
  background: #1a6fff22; border: 1px solid #1a6fff; color: #1a6fff;
  padding: 6px 12px; border-radius: 6px; font-size: .82rem;
  cursor: pointer; transition: background .2s; white-space: nowrap;
}
.copy-btn:hover { background: #1a6fff44; }

/* pay fields */
.pay-field { margin-bottom: 13px; }
.pay-field label {
  display: block; font-size: .8rem; color: #8ba3c4;
  margin-bottom: 5px; font-weight: 600;
}
.pay-field input[type=text],
.pay-field textarea {
  width: 100%; padding: 11px 14px;
  background: #111c35; border: 1px solid #1e2e50;
  color: #fff; border-radius: 8px; font-size: .9rem;
  outline: none; transition: border .2s; font-family: inherit; resize: vertical;
}
.pay-field input:focus, .pay-field textarea:focus { border-color: #1a6fff; }
.pay-field input.is-error { border-color: #ff4c4c !important; }
.pay-field .perr { display: none; font-size: .75rem; color: #ff5c5c; margin-top: 3px; }
.pay-field .perr.show { display: block; }

/* upload */
.upload-area {
  border: 2px dashed #1e2e50; border-radius: 8px;
  padding: 18px; text-align: center; cursor: pointer;
  transition: border-color .2s; position: relative; background: #111c35;
}
.upload-area:hover, .upload-area.has-file { border-color: #1a6fff; }
.upload-area.is-error { border-color: #ff4c4c; }
.upload-area input[type=file] {
  position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.upload-area i { font-size: 1.8rem; color: #1a6fff; margin-bottom: 5px; display: block; }
.upload-area p { color: #8ba3c4; font-size: .83rem; margin: 0; }
.upload-area .file-name { color: #1a6fff; font-size: .79rem; margin-top: 4px; word-break: break-all; }

.btn-pay-submit {
  width: 100%; padding: 13px;
  background: linear-gradient(135deg, #00c853, #00962e);
  border: none; border-radius: 8px; color: #fff;
  font-size: 1rem; font-weight: 700; cursor: pointer;
  transition: opacity .2s, transform .1s; margin-top: 6px;
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-pay-submit:hover:not(:disabled) { opacity: .9; transform: translateY(-1px); }
.btn-pay-submit:disabled { opacity: .5; cursor: not-allowed; }

/* ── success popup overlay (inside payment modal) ── */
.pay-success-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.88); z-index: 10000;
  align-items: center; justify-content: center; padding: 20px;
}
.pay-success-overlay.active { display: flex; }
.pay-success-box {
  background: #0d1425; border: 1px solid #00c85355;
  border-radius: 16px; padding: 36px 28px; text-align: center;
  max-width: 360px; width: 100%; animation: popIn .3s ease;
}
@keyframes popIn {
  from { transform: scale(.85); opacity: 0; }
  to   { transform: scale(1);   opacity: 1; }
}
.pay-success-box .big-check {
  width: 72px; height: 72px;
  background: linear-gradient(135deg, #00c853, #00962e);
  border-radius: 50%; display: flex; align-items: center;
  justify-content: center; margin: 0 auto 18px; font-size: 2.2rem;
}
.pay-success-box h3 { font-size: 1.25rem; color: #fff; margin-bottom: 10px; }
.pay-success-box p  { color: #8ba3c4; font-size: .88rem; line-height: 1.7; margin-bottom: 22px; }
.countdown-ring {
  width: 44px; height: 44px; margin: 0 auto 14px;
  position: relative;
}
.countdown-ring svg { transform: rotate(-90deg); }
.countdown-ring circle {
  fill: none; stroke: #1e2e50; stroke-width: 4;
}
.countdown-ring .progress {
  stroke: #1a6fff; stroke-width: 4;
  stroke-dasharray: 113; stroke-dashoffset: 0;
  transition: stroke-dashoffset 1s linear;
  stroke-linecap: round;
}
.countdown-num {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; font-weight: 700; color: #1a6fff;
}
.pay-success-box .redirect-note {
  font-size: .78rem; color: #5a7a9a; margin-bottom: 0;
}

::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: #05080f; }
::-webkit-scrollbar-thumb { background: #1e2e50; border-radius: 4px; }
</style>
</head>
<body>

<div class="container">
  <h2>Member Registration</h2>

  <form id="regForm" novalidate>
    <?php echo csrf_field(); ?>

    
    <div class="field-wrap">
      <label class="field-label">Sponsor <span style="color:#ff5c5c">*</span></label>
      <select name="sponsor_type" id="sponsorType">
        <option value="">— Select Option —</option>
        <option value="no_sponsor">I don't have a Sponsor</option>
        <option value="has_sponsor">I have a Sponsor</option>
      </select>
      <div class="err-msg" id="err_sponsor_type">Please select a sponsor option.</div>

      
      <div id="adminSponsorCard">
        <b>Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <span>Admin</span><br>
        <b>Member ID&nbsp;:</b> <span class="sc-mid">SB0783633087</span>
      </div>
    </div>

    
    <div class="field-wrap" id="sponsorInputWrap">
      <label class="field-label">
        Search Sponsor <span style="color:#ff5c5c">*</span>
        <span style="color:#5a7a9a;font-weight:400;font-size:.74rem"> — name, phone, email or ID</span>
      </label>

      <div class="sponsor-input-row">
        <input type="text" id="sponsorIdInput" placeholder="Type to search sponsor…" autocomplete="off">
        <div class="sponsor-spinner" id="sponsorSpinner"></div>

        
        <div id="sponsorDropdown"></div>
      </div>

      <div class="err-msg" id="err_sponsor_id">Please search and select a valid Sponsor.</div>

      
      <div id="sponsorCard">
        <span class="sc-not-found" id="sc_not_found" style="display:none">
          <i class="bi bi-x-circle"></i> No member found with this ID.
        </span>
        <span id="sc_found_content" style="display:none">
          <b>Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <span id="sc_name"></span><br>
          <b>Mobile&nbsp;&nbsp;&nbsp;&nbsp;:</b> <span id="sc_phone"></span><br>
          <b>Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <span id="sc_email"></span><br>
          <b>Member ID&nbsp;:</b> <span class="sc-mid" id="sc_memberid"></span><br>
          <b>Sponsor ID&nbsp;:</b> <span id="sc_sponsorid"></span>
        </span>
        
        <button type="button" id="clearSponsorBtn" style="display:none !important; visibility:hidden; position:absolute;">
          <i class="bi bi-x-lg"></i> Clear &amp; search again
        </button>
      </div>
    </div>

    <input type="hidden" name="sponser_id"   id="hSponsorId">
    <input type="hidden" name="sponser_name" id="hSponsorName">

    
    <div class="field-wrap">
      <label class="field-label">Full Name <span style="color:#ff5c5c">*</span></label>
      <input type="text" name="name" id="fieldName" placeholder="Full Name" autocomplete="off">
      <div class="err-msg" id="err_name">Full name is required (min 3 characters).</div>
    </div>

    
    <div class="field-wrap">
      <label class="field-label">Phone Number <span style="color:#ff5c5c">*</span></label>
        <select name="country_code" id="countryCodeSelect">
          <option value="" data-name=""> Select Country</option>
          <option value="+91"  data-len="10" data-name="India">🇮🇳 +91 India</option>
          <option value="+1"   data-len="10" data-name="USA/Canada">🇺🇸 +1 USA</option>
          <option value="+44"  data-len="10" data-name="UK">🇬🇧 +44 UK</option>
          <option value="+880" data-len="10" data-name="Bangladesh">🇧🇩 +880 Bangladesh</option>
          <option value="+92"  data-len="10" data-name="Pakistan">🇵🇰 +92 Pakistan</option>
          <option value="+971" data-len="9"  data-name="UAE">🇦🇪 +971 UAE</option>
          <option value="+966" data-len="9"  data-name="Saudi Arabia">🇸🇦 +966 Saudi Arabia</option>
          <option value="+60"  data-len="10" data-name="Malaysia">🇲🇾 +60 Malaysia</option>
          <option value="+65"  data-len="8"  data-name="Singapore">🇸🇬 +65 Singapore</option>
          <option value="+61"  data-len="9"  data-name="Australia">🇦🇺 +61 Australia</option>
          <option value="+81"  data-len="10" data-name="Japan">🇯🇵 +81 Japan</option>
          <option value="+86"  data-len="11" data-name="China">🇨🇳 +86 China</option>
          <option value="+7"   data-len="10" data-name="Russia">🇷🇺 +7 Russia</option>
          <option value="+49"  data-len="11" data-name="Germany">🇩🇪 +49 Germany</option>
          <option value="+33"  data-len="9"  data-name="France">🇫🇷 +33 France</option>
          <option value="+39"  data-len="10" data-name="Italy">🇮🇹 +39 Italy</option>
          <option value="+34"  data-len="9"  data-name="Spain">🇪🇸 +34 Spain</option>
          <option value="+55"  data-len="11" data-name="Brazil">🇧🇷 +55 Brazil</option>
          <option value="+27"  data-len="9"  data-name="South Africa">🇿🇦 +27 South Africa</option>
          <option value="+234" data-len="10" data-name="Nigeria">🇳🇬 +234 Nigeria</option>
          <option value="+254" data-len="9"  data-name="Kenya">🇰🇪 +254 Kenya</option>
          <option value="+20"  data-len="10" data-name="Egypt">🇪🇬 +20 Egypt</option>
          <option value="+98"  data-len="10" data-name="Iran">🇮🇷 +98 Iran</option>
          <option value="+62"  data-len="10" data-name="Indonesia">🇮🇩 +62 Indonesia</option>
          <option value="+63"  data-len="10" data-name="Philippines">🇵🇭 +63 Philippines</option>
          <option value="+84"  data-len="10" data-name="Vietnam">🇻🇳 +84 Vietnam</option>
          <option value="+66"  data-len="9"  data-name="Thailand">🇹🇭 +66 Thailand</option>
          <option value="+94"  data-len="9"  data-name="Sri Lanka">🇱🇰 +94 Sri Lanka</option>
          <option value="+977" data-len="10" data-name="Nepal">🇳🇵 +977 Nepal</option>
          <option value="+95"  data-len="9"  data-name="Myanmar">🇲🇲 +95 Myanmar</option>
        </select>

      <div class="err-msg" id="err_country">Please select country.</div>
    </div>

    
    <div class="field-wrap">
      <label class="field-label">Phone No <span style="color:#5a7a9a; font-weight:400">*</span></label>
      <input type="tel" name="phone" id="fieldPhone" placeholder="Phone number" maxlength="15" autocomplete="off">
      <span class="phone-hint" id="phoneHint"></span>
      <div class="err-msg" id="err_phone"></div>
    </div>

    
    <div class="field-wrap">
      <label class="field-label">Email <span style="color:#5a7a9a; font-weight:400">(Optional)</span></label>
      <input type="email" name="email" id="fieldEmail" placeholder="Email address" autocomplete="off">
      <div class="err-msg" id="err_email">Please enter a valid email address.</div>
    </div>
    
    
    <div class="field-wrap">
      <label class="field-label">Referral Code <span style="color:#5a7a9a; font-weight:400">(Optional)</span></label>
      <input type="text" name="referral_code" id="fieldReferral" placeholder="Eg: DUYYVHDD">
      <div id="referralMsg" style="margin-top:5px; font-size:14px;"></div>
    </div>

    
    <div class="field-wrap">
      <label class="field-label">Age Group <span style="color:#ff5c5c">*</span></label>
      <select name="date_of_birth" id="fieldAge">
        <option value="">Select Age Group</option>
        <option value="Below 18">Below 18</option>
        <option value="18-25">18 – 25</option>
        <option value="26-35">26 – 35</option>
        <option value="36-50">36 – 50</option>
        <option value="Above 50">Above 50</option>
      </select>
      <div class="err-msg" id="err_age">Please select your age group.</div>
    </div>

    
    <div class="field-wrap">
      <label class="field-label">Gender <span style="color:#ff5c5c">*</span></label>
      <select name="gender" id="fieldGender">
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
      </select>
      <div class="err-msg" id="err_gender">Please select your gender.</div>
    </div>

    
    <div class="terms-row" id="termsRow">
      <input type="checkbox" id="termsCheck">
      <label for="termsCheck">
        I agree to the <a id="openTerms">Terms &amp; Conditions</a>
        of SmartBoat Ecosystem. By registering I confirm all details are correct.
      </label>
    </div>
    <div class="err-msg" id="err_terms" style="margin-top:-12px; margin-bottom:14px;">
      You must accept the Terms &amp; Conditions.
    </div>

    
    <button type="submit" id="submitBtn">
      <i class="bi bi-person-check-fill"></i>&nbsp; Register &amp; Proceed to Payment
    </button>

                <a href="<?php echo e(route('login')); ?>" style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;border:1px solid #1e2e50;border-radius:8px;color:#8ba3c4;font-size:.93rem;font-weight:500;text-decoration:none;margin-top:10px;text-align:center;transition:background .15s;" onmouseover="this.style.background='#111c35'" onmouseout="this.style.background='transparent'">
  <i class="bi bi-arrow-left"></i> Back to Login
</a>
  </form>
</div>



<div class="modal-overlay" id="termsModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3><i class="bi bi-shield-check" style="color:#1a6fff"></i>&nbsp; Terms &amp; Conditions</h3>
      <button class="modal-close-x" id="closeTermsX"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="modal-body">
      <p><strong>1. Eligibility:</strong> You must be 18 years or older to register as a member of SmartBoat Ecosystem.</p>
      <p><strong>2. Accurate Information:</strong> All information provided during registration must be true, accurate and up to date. SmartBoat Ecosystem is not responsible for consequences arising from incorrect details.</p>
      <p><strong>3. Account Security:</strong> You are responsible for maintaining the confidentiality of your Member ID and password. Do not share credentials with any third party.</p>
      <p><strong>4. Sponsor Relationship:</strong> If you have been referred by a Sponsor, their details must be correctly entered. False sponsorship information may result in account termination.</p>
      <p><strong>5. Verification:</strong> Your account will remain in <em>Pending</em> status until admin verification is complete. You will be notified once activated.</p>
      <p><strong>6. Code of Conduct:</strong> Members must not engage in fraudulent, abusive, or illegal activities within the SmartBoat platform.</p>
      <p><strong>7. Privacy:</strong> Personal information is collected solely for operational purposes and will not be sold to third parties.</p>
      <p><strong>8. Amendments:</strong> SmartBoat Ecosystem reserves the right to update these terms at any time. Continued use constitutes acceptance.</p>
      <button class="modal-close-btn" id="closeTermsBtn">I Understand — Close</button>
    </div>
  </div>
</div>



<div class="modal-overlay" id="paymentModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3><i class="bi bi-credit-card-2-front" style="color:#1a6fff"></i>&nbsp; Payment Details</h3>
    </div>
    <div class="modal-body">

      <div class="badge"><i class="bi bi-clock"></i>&nbsp; Pending Verification</div>

      
      <div class="member-summary">
        <div class="mrow"><span class="lbl">Name</span>                 <span class="val"  id="pm_name">—</span></div>
        <div class="mrow"><span class="lbl">Member ID</span>            <span class="val mid" id="pm_mid">—</span></div>
        <div class="mrow"><span class="lbl">Password</span>             <span class="val pass" id="pm_pass">—</span></div>
        <div class="mrow"><span class="lbl">Transaction Password</span> <span class="val txn"  id="pm_txn">—</span></div>
        <div class="mrow"><span class="lbl">Amount Payable</span>       <span class="val amt"  id="pm_amount">As per plan</span></div>
        <div class="mrow"><span class="lbl">Status</span>               <span class="val pend">Pending</span></div>
      </div>

      <div class="info-note">
        <i class="bi bi-info-circle-fill"></i>
        <span>Please save your Member ID, Password &amp; Transaction Password. Complete payment and upload your screenshot for admin verification.</span>
      </div>

      
      <div class="payment-qr">
        <img
          src="<?php echo e(asset('public/admin/assets/images/HindolMukherjeeQRCode.png')); ?>"
          alt="Scan to Pay"
        >
        <p>Scan with PhonePe / GPay / Paytm / any UPI app</p>
      </div>

      
      <div class="upi-badge">
        <div><small>UPI ID</small><span id="upiIdText">mukherjeehindol@ybl</span></div>
        <button class="copy-btn" id="copyUpiBtn"><i class="bi bi-copy"></i> Copy</button>
      </div>

      <div class="section-divider"><i class="bi bi-upload"></i> Upload Proof</div>

      
      <div class="pay-field">
        <label>UTR / Transaction Number <span style="color:#ff5c5c">*</span></label>
        <input type="text" id="payUtr" placeholder="Enter UTR / Ref number" maxlength="50" autocomplete="off">
        <div class="perr" id="err_utr">UTR / Transaction number is required.</div>
      </div>

      
      <div class="pay-field">
        <label>Amount Payable (₹) – Minimum ₹1 or above <span style="color:#ff5c5c">*</span></label>
        <input type="text" id="payAmount" placeholder="e.g. 999" maxlength="10" autocomplete="off">
        <div class="perr" id="err_amount">Please enter the amount paid (numbers only).</div>
      </div>

      
      <div class="pay-field">
        <label>Payment Screenshot <span style="color:#ff5c5c">*</span></label>
        <div class="upload-area" id="uploadArea">
          <input type="file" id="screenshotFile" accept="image/jpeg,image/png,image/webp,image/jpg">
          <i class="bi bi-cloud-upload"></i>
          <p>Click to upload or drag &amp; drop<br><small style="color:#5a7a9a">JPG, PNG, WEBP — max 5 MB</small></p>
          <div class="file-name" id="fileName"></div>
        </div>
        <div class="perr" id="err_screenshot">Please upload a payment screenshot (JPG/PNG/WEBP, max 5 MB).</div>
      </div>

      
      <div class="pay-field">
        <label>Message <span style="color:#5a7a9a; font-weight:400">(Optional)</span></label>
        <textarea id="payMessage" rows="2" placeholder="Any note for admin…"></textarea>
      </div>

      
      <button class="btn-pay-submit" id="submitPayBtn">
        <i class="bi bi-send-check-fill"></i> Submit Payment Proof
      </button>

    </div>
  </div>
</div>



<div class="pay-success-overlay" id="paySuccessOverlay">
  <div class="pay-success-box">
    <div class="big-check">✓</div>
    <h3>Payment Submitted!</h3>
    <p>Your payment proof has been submitted successfully.<br>
       Admin will verify and activate your account shortly.<br>
       <strong style="color:#fff">Please note down your credentials above.</strong></p>
    <div class="countdown-ring">
      <svg width="44" height="44" viewBox="0 0 44 44">
        <circle cx="22" cy="22" r="18"/>
        <circle class="progress" id="cdCircle" cx="22" cy="22" r="18"/>
      </svg>
      <div class="countdown-num" id="cdNum">5</div>
    </div>
    <p class="redirect-note">Redirecting to login in <span id="cdSec">5</span> seconds…</p>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 /* ══════════════════════════════════════════════
   Referral Code Verification
══════════════════════════════════════════════ */   

$(document).on('input', '#fieldReferral', function () {
    let code = $(this).val();
    $.ajax({
        url: '<?php echo e(route("sponsor.check-referral-verification")); ?>',
        method: 'GET',
        data: {
            _token: '<?php echo e(csrf_token()); ?>',
            referral_code: code
        },
        success: function (res) {
            if (res.status) {
                $('#referralMsg')
                    .html('Valid Referral Code ✅')
                    .css('color', 'green');
            } else {
                $('#referralMsg')
                    .html('Invalid Referral Code ❌')
                    .css('color', 'red');
            }
        }
    });
});

/* ══════════════════════════════════════════════
   MODAL OPEN / CLOSE + BODY LOCK
══════════════════════════════════════════════ */
function openModal(id) {
  document.getElementById(id).classList.add('active');
  document.body.classList.add('modal-open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('active');
  if (!document.querySelector('.modal-overlay.active'))
    document.body.classList.remove('modal-open');
}

/* ══════════════════════════════════════════════
   TERMS MODAL
══════════════════════════════════════════════ */
document.getElementById('openTerms').addEventListener('click', () => openModal('termsModal'));
document.getElementById('closeTermsX').addEventListener('click', () => closeModal('termsModal'));
document.getElementById('closeTermsBtn').addEventListener('click', () => closeModal('termsModal'));
document.getElementById('termsModal').addEventListener('click', function(e) {
  if (e.target === this) closeModal('termsModal');
});

/* ══════════════════════════════════════════════
   PHONE LENGTH MAP  (data-len from <option>)
══════════════════════════════════════════════ */
const ccSelect  = document.getElementById('countryCodeSelect');
const phoneInp  = document.getElementById('fieldPhone');
const phoneHint = document.getElementById('phoneHint');
const errPhone  = document.getElementById('err_phone');

function getExpectedLen() {
  const opt = ccSelect.options[ccSelect.selectedIndex];
  return parseInt(opt.dataset.len || '10', 10);
}
function getCountryName() {
  const opt = ccSelect.options[ccSelect.selectedIndex];
  return opt.dataset.name || '';
}

function validatePhone(showErr) {
  const raw    = phoneInp.value.replace(/\D/g, '');   // digits only
  const expLen = getExpectedLen();
  const cname  = getCountryName();

  phoneHint.className = 'phone-hint';
  phoneHint.textContent = `${cname} numbers must be ${expLen} digits.`;

  if (raw.length === 0) {
    phoneInp.classList.remove('is-error','is-ok');
    if (showErr) { errPhone.textContent='Phone number is required.'; errPhone.classList.add('show'); }
    return false;
  }
  if (raw.length !== expLen) {
    phoneInp.classList.add('is-error'); phoneInp.classList.remove('is-ok');
    phoneHint.classList.add('error');
    if (showErr) { errPhone.textContent=`Must be exactly ${expLen} digits for ${cname}.`; errPhone.classList.add('show'); }
    return false;
  }
  phoneInp.classList.add('is-ok'); phoneInp.classList.remove('is-error');
  phoneHint.classList.add('ok');
  errPhone.classList.remove('show');
  return true;
}

ccSelect.addEventListener('change', () => validatePhone(false));
phoneInp.addEventListener('input', () => { validatePhone(false); });
phoneInp.addEventListener('keypress', e => { if (!/[0-9]/.test(e.key)) e.preventDefault(); });
phoneInp.addEventListener('blur', () => validatePhone(true));

/* show hint on load */
window.addEventListener('DOMContentLoaded', () => {
  phoneHint.textContent = `${getCountryName()} numbers must be ${getExpectedLen()} digits.`;
});

/* ══════════════════════════════════════════════
   SPONSOR TYPE TOGGLE
══════════════════════════════════════════════ */
const sponsorType       = document.getElementById('sponsorType');
const sponsorInputWrap  = document.getElementById('sponsorInputWrap');
const adminSponsorCard  = document.getElementById('adminSponsorCard');
const hSponsorId        = document.getElementById('hSponsorId');
const hSponsorName      = document.getElementById('hSponsorName');
const sponsorCard       = document.getElementById('sponsorCard');
const sponsorSpinner    = document.getElementById('sponsorSpinner');
const sponsorIdInput    = document.getElementById('sponsorIdInput');
const sponsorDropdown   = document.getElementById('sponsorDropdown');
const clearSponsorBtn   = document.getElementById('clearSponsorBtn');

/* Default admin sponsor constants */
const ADMIN_SPONSOR_ID   = 'SB0783633087';
const ADMIN_SPONSOR_NAME = 'HINDOL MUKHERJEE';

sponsorType.addEventListener('change', function () {
  document.getElementById('err_sponsor_type').classList.remove('show');

  if (this.value === 'has_sponsor') {
    /* Show search section, hide admin card, clear admin defaults */
    sponsorInputWrap.style.display = 'block';
    adminSponsorCard.style.display = 'none';
    hSponsorId.value   = '';
    hSponsorName.value = '';
    resetSponsorField();

  } else if (this.value === 'no_sponsor') {
    /* Hide search section, show admin card, set fixed admin sponsor */
    sponsorInputWrap.style.display = 'none';
    adminSponsorCard.style.display = 'block';
    hSponsorId.value   = ADMIN_SPONSOR_ID;
    hSponsorName.value = ADMIN_SPONSOR_NAME;
    resetSponsorField();

  } else {
    /* Nothing selected — hide everything */
    sponsorInputWrap.style.display = 'none';
    adminSponsorCard.style.display = 'none';
    hSponsorId.value   = '';
    hSponsorName.value = '';
    resetSponsorField();
  }
});

/* ── Reset sponsor search field fully ── */
function resetSponsorField() {
  sponsorIdInput.value          = '';
  sponsorCard.style.display     = 'none';
  sponsorDropdown.style.display = 'none';
  document.getElementById('sc_not_found').style.display     = 'none';
  document.getElementById('sc_found_content').style.display = 'none';
  document.getElementById('err_sponsor_id').classList.remove('show');
}

/* Clear button — kept for JS reference only, never visible */
clearSponsorBtn.addEventListener('click', function () {
  resetSponsorField();
  sponsorIdInput.focus();
});

/* ══════════════════════════════════════════════
   SPONSOR AUTOCOMPLETE — SEARCH BY NAME /
   PHONE / EMAIL / MEMBER ID / SPONSOR ID
══════════════════════════════════════════════ */

/* Highlight matched substring */
function hlText(text, query) {
  if (!query || !text) return text || '';
  const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  return text.replace(new RegExp('(' + escaped + ')', 'gi'),
    '<mark class="sp-hl">$1</mark>');
}

let spDebounce  = null;
let spActiveIdx = -1;

sponsorIdInput.addEventListener('input', function () {
  clearTimeout(spDebounce);
  spActiveIdx = -1;
  const q = this.value.trim();

  /* If user edits after a selection, clear the confirmed values */
  if (hSponsorId.value) {
    hSponsorId.value   = '';
    hSponsorName.value = '';
    sponsorCard.style.display = 'none';
  }

  if (q.length < 2) {
    sponsorDropdown.style.display = 'none';
    sponsorSpinner.style.display  = 'none';
    return;
  }

  sponsorSpinner.style.display = 'block';
  spDebounce = setTimeout(() => fetchSponsorResults(q), 400);
});

/* Keyboard navigation inside dropdown */
sponsorIdInput.addEventListener('keydown', function (e) {
  const items = sponsorDropdown.querySelectorAll('.sp-item');
  if (!items.length || sponsorDropdown.style.display === 'none') return;

  if (e.key === 'ArrowDown') {
    e.preventDefault();
    spActiveIdx = Math.min(spActiveIdx + 1, items.length - 1);
    items.forEach((el, i) => el.classList.toggle('sp-active', i === spActiveIdx));
    items[spActiveIdx] && items[spActiveIdx].scrollIntoView({ block: 'nearest' });
  } else if (e.key === 'ArrowUp') {
    e.preventDefault();
    spActiveIdx = Math.max(spActiveIdx - 1, 0);
    items.forEach((el, i) => el.classList.toggle('sp-active', i === spActiveIdx));
    items[spActiveIdx] && items[spActiveIdx].scrollIntoView({ block: 'nearest' });
  } else if (e.key === 'Enter' && spActiveIdx >= 0) {
    e.preventDefault();
    items[spActiveIdx].click();
  } else if (e.key === 'Escape') {
    sponsorDropdown.style.display = 'none';
  }
});

/* Fetch from sponsor.search route */
async function fetchSponsorResults(q) {
  try {
    const res  = await fetch('<?php echo e(route("sponsor.search")); ?>?q=' + encodeURIComponent(q), {
      headers: {
        'Accept'       : 'application/json',
        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content
      }
    });
    const list = await res.json();

    sponsorDropdown.innerHTML = '';
    spActiveIdx = -1;

    if (!Array.isArray(list) || list.length === 0) {
      sponsorDropdown.innerHTML = '<div class="sp-no-result"><i class="bi bi-x-circle"></i>&nbsp; No member found matching "<strong>' + q + '</strong>"</div>';
      sponsorDropdown.style.display = 'block';
      return;
    }

    list.forEach(function (m) {
      const div       = document.createElement('div');
      div.className   = 'sp-item';
      const nameHL    = hlText(m.name,     q);
      const idHL      = hlText(m.memberID, q);
      const phoneHL   = hlText(m.phone,    q);
      const emailHL   = hlText(m.email || '', q);
      div.innerHTML   =
        '<div class="sp-name">' + nameHL + '</div>' +
        '<div class="sp-meta">' +
          '<span class="sp-id">' + idHL + '</span>' +
          ' &nbsp;·&nbsp; ' + phoneHL +
          (m.email ? ' &nbsp;·&nbsp; ' + emailHL : '') +
        '</div>';
      div.addEventListener('mousedown', function (e) {
        /* mousedown fires before blur so dropdown stays open long enough */
        e.preventDefault();
        selectSponsor(m, q);
      });
      sponsorDropdown.appendChild(div);
    });

    sponsorDropdown.style.display = 'block';

  } catch (err) {
    sponsorDropdown.style.display = 'none';
  } finally {
    sponsorSpinner.style.display = 'none';
  }
}

/* Called when user clicks a dropdown row */
function selectSponsor(m) {
  /* Store hidden values */
  hSponsorId.value   = m.memberID;
  hSponsorName.value = m.name;

  /* Update text input to show selected member */
  sponsorIdInput.value = m.memberID + '  —  ' + m.name;

  /* Close dropdown */
  sponsorDropdown.style.display = 'none';

  /* Populate confirmation card */
  document.getElementById('sc_not_found').style.display     = 'none';
  document.getElementById('sc_found_content').style.display = 'inline';
  document.getElementById('sc_name').textContent      = m.name;
  document.getElementById('sc_phone').textContent     = m.phone;
  document.getElementById('sc_email').textContent     = m.email || '—';
  document.getElementById('sc_memberid').textContent  = m.memberID;
  document.getElementById('sc_sponsorid').textContent = m.sponser_id || '—';
  sponsorCard.classList.remove('error-card');
  sponsorCard.style.display = 'block';

  /* Clear any error */
  document.getElementById('err_sponsor_id').classList.remove('show');
}

/* Close dropdown when clicking outside the sponsor section */
document.addEventListener('click', function (e) {
  if (sponsorInputWrap && !sponsorInputWrap.contains(e.target)) {
    sponsorDropdown.style.display = 'none';
  }
});

/* ══════════════════════════════════════════════
   REGISTRATION FORM VALIDATION
══════════════════════════════════════════════ */
function showErr(id, msg) {
  const el = document.getElementById(id);
  if (msg) el.textContent = msg;
  el.classList.add('show');
}
function hideErr(id) { document.getElementById(id).classList.remove('show'); }

function validateRegForm() {
  let ok = true;

  /* sponsor type */
  if (!sponsorType.value) { showErr('err_sponsor_type'); ok = false; }
  else hideErr('err_sponsor_type');

  /* sponsor id (only when has_sponsor) */
  if (sponsorType.value === 'has_sponsor') {
    if (!hSponsorId.value) {
      showErr('err_sponsor_id', 'Please search and select a valid Sponsor from the list.');
      ok = false;
    } else hideErr('err_sponsor_id');
  }

  /* name */
  const nameVal = document.getElementById('fieldName').value.trim();
  if (nameVal.length < 3) {
    document.getElementById('fieldName').classList.add('is-error');
    showErr('err_name'); ok = false;
  } else {
    document.getElementById('fieldName').classList.remove('is-error');
    hideErr('err_name');
  }

  /* country */
  if (!document.getElementById('countryCodeSelect').value) {
    document.getElementById('countryCodeSelect').classList.add('is-error');
    showErr('err_country'); ok = false;
  } else {
    document.getElementById('countryCodeSelect').classList.remove('is-error');
    hideErr('err_country');
  }

  /* phone */
  if (!validatePhone(true)) ok = false;

  /* email (optional but if filled must be valid) */
  const emailVal = document.getElementById('fieldEmail').value.trim();
  if (emailVal && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
    document.getElementById('fieldEmail').classList.add('is-error');
    showErr('err_email'); ok = false;
  } else {
    document.getElementById('fieldEmail').classList.remove('is-error');
    hideErr('err_email');
  }

  /* age */
  if (!document.getElementById('fieldAge').value) {
    document.getElementById('fieldAge').classList.add('is-error');
    showErr('err_age'); ok = false;
  } else {
    document.getElementById('fieldAge').classList.remove('is-error');
    hideErr('err_age');
  }

  /* gender */
  if (!document.getElementById('fieldGender').value) {
    document.getElementById('fieldGender').classList.add('is-error');
    showErr('err_gender'); ok = false;
  } else {
    document.getElementById('fieldGender').classList.remove('is-error');
    hideErr('err_gender');
  }

  /* terms */
  if (!document.getElementById('termsCheck').checked) {
    document.getElementById('termsRow').classList.add('is-error');
    showErr('err_terms'); ok = false;
  } else {
    document.getElementById('termsRow').classList.remove('is-error');
    hideErr('err_terms');
  }

  return ok;
}

/* ══════════════════════════════════════════════
   REGISTRATION SUBMIT
══════════════════════════════════════════════ */
let registeredMemberId = '';
const submitBtn = document.getElementById('submitBtn');

document.getElementById('regForm').addEventListener('submit', async function (e) {
  e.preventDefault();
  if (!validateRegForm()) {
    /* scroll to first error */
    const firstErr = document.querySelector('.err-msg.show, .is-error');
    if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  submitBtn.disabled  = true;
  submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i>&nbsp; Registering…';

  try {
    const res    = await fetch('<?php echo e(route("register.post")); ?>', {
      method : 'POST',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
      body   : new FormData(this),
    });
    const result = await res.json();

    if (result.success) {
      document.getElementById('pm_name').textContent   = result.name;
      document.getElementById('pm_mid').textContent    = result.memberID;
      document.getElementById('pm_pass').textContent   = result.password;
      document.getElementById('pm_txn').textContent    = result.transaction_password;
      document.getElementById('pm_amount').textContent = result.amount ? '₹ ' + result.amount : 'As per plan';
      registeredMemberId = result.memberID;
      openModal('paymentModal');
    } else {
      const errors = result.errors
        ? Object.values(result.errors).flat().join('\n')
        : (result.message || 'Registration failed.');
      alert(errors);
    }
  } catch (err) {
    alert('Something went wrong. Please try again.');
  } finally {
    submitBtn.disabled  = false;
    submitBtn.innerHTML = '<i class="bi bi-person-check-fill"></i>&nbsp; Register &amp; Proceed to Payment';
  }
});

/* ══════════════════════════════════════════════
   COPY UPI
══════════════════════════════════════════════ */
document.getElementById('copyUpiBtn').addEventListener('click', function () {
  navigator.clipboard.writeText(document.getElementById('upiIdText').textContent).then(() => {
    this.innerHTML = '<i class="bi bi-check2"></i> Copied!';
    setTimeout(() => { this.innerHTML = '<i class="bi bi-copy"></i> Copy'; }, 2000);
  });
});

/* ══════════════════════════════════════════════
   FILE UPLOAD DISPLAY + VALIDATION
══════════════════════════════════════════════ */
document.getElementById('screenshotFile').addEventListener('change', function () {
  const f = this.files[0];
  const uploadArea = document.getElementById('uploadArea');
  const errEl = document.getElementById('err_screenshot');

  if (!f) { document.getElementById('fileName').textContent = ''; uploadArea.classList.remove('has-file'); return; }

  /* size check — 5 MB */
  if (f.size > 5 * 1024 * 1024) {
    errEl.classList.add('show');
    uploadArea.classList.add('is-error');
    document.getElementById('fileName').textContent = '';
    this.value = '';
    return;
  }
  errEl.classList.remove('show');
  uploadArea.classList.remove('is-error');
  uploadArea.classList.add('has-file');
  document.getElementById('fileName').textContent = f.name;
});

/* ══════════════════════════════════════════════
   PAYMENT FORM VALIDATION
══════════════════════════════════════════════ */
function validatePaymentForm() {
  let ok = true;
  const utr        = document.getElementById('payUtr').value.trim();
  const amount     = document.getElementById('payAmount').value.trim();
  const screenshot = document.getElementById('screenshotFile').files[0];

  /* UTR */
  if (!utr) {
    document.getElementById('payUtr').classList.add('is-error');
    document.getElementById('err_utr').classList.add('show'); ok = false;
  } else {
    document.getElementById('payUtr').classList.remove('is-error');
    document.getElementById('err_utr').classList.remove('show');
  }

  if (!amount || !/^\d+(\.\d{1,2})?$/.test(amount)) {
    document.getElementById('payAmount').classList.add('is-error');
    document.getElementById('err_amount').classList.add('show'); ok = false;
  } else {
    document.getElementById('payAmount').classList.remove('is-error');
    document.getElementById('err_amount').classList.remove('show');
  }

  if (!screenshot) {
    document.getElementById('uploadArea').classList.add('is-error');
    document.getElementById('err_screenshot').classList.add('show'); ok = false;
  } else {
    document.getElementById('uploadArea').classList.remove('is-error');
    document.getElementById('err_screenshot').classList.remove('show');
  }

  return ok;
}

/* ══════════════════════════════════════════════
   SUBMIT PAYMENT PROOF
══════════════════════════════════════════════ */
document.getElementById('submitPayBtn').addEventListener('click', async function () {
  if (!validatePaymentForm()) return;

  this.disabled  = true;
  this.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting…';

  try {
    const fd = new FormData();
    fd.append('_token',                          document.querySelector('meta[name="csrf-token"]').content);
    fd.append('member_id',                       registeredMemberId);
    fd.append('payment_utr_no',                  document.getElementById('payUtr').value.trim());
    fd.append('amount',                          document.getElementById('payAmount').value.trim());
    fd.append('verification_payment_screenshot', document.getElementById('screenshotFile').files[0]);
    fd.append('verification_message',            document.getElementById('payMessage').value.trim());

    const res    = await fetch('<?php echo e(route("payment.submit")); ?>', {
      method : 'POST',
      headers: { 'Accept': 'application/json' },
      body   : fd,
    });
    const result = await res.json();

    if (result.success) {
      /* Show full-screen success overlay with countdown */
      document.getElementById('paySuccessOverlay').classList.add('active');
      startCountdown(5, '<?php echo e(route("login")); ?>');
    } else {
      alert(result.message || 'Payment submission failed. Please try again.');
    }
  } catch (err) {
    alert('Server error. Please try again.');
  } finally {
    this.disabled  = false;
    this.innerHTML = '<i class="bi bi-send-check-fill"></i> Submit Payment Proof';
  }
});

/* ══════════════════════════════════════════════
   COUNTDOWN → REDIRECT
══════════════════════════════════════════════ */
function startCountdown(secs, url) {
  const circle  = document.getElementById('cdCircle');
  const numEl   = document.getElementById('cdNum');
  const secEl   = document.getElementById('cdSec');
  const total   = 113; /* circumference = 2π×18 ≈ 113 */
  let remaining = secs;

  circle.style.strokeDashoffset = '0';

  const tick = setInterval(() => {
    remaining--;
    numEl.textContent = remaining;
    secEl.textContent = remaining;
    /* animate ring depletion */
    circle.style.strokeDashoffset = ((secs - remaining) / secs * total).toString();

    if (remaining <= 0) {
      clearInterval(tick);
      window.location.href = url;
    }
  }, 1000);
}
</script>

</body>
</html>
<?php /**PATH E:\xampp\htdocs\16-04-2026\resources\views/admin/register.blade.php ENDPATH**/ ?>