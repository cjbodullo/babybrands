<link rel="stylesheet" href="public/winner-photo-release.css?v=<?php echo filemtime(__DIR__ . '/../public/winner-photo-release.css'); ?>">
<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<!-- GLightbox JS -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<?php
$dbConfig = require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/WinnerFetchController.php';

$controller = new WinnerFetchController($dbConfig);
$winners = $controller->getApprovedWinners(4);

function esc_html($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
function esc_url($url) {
    return filter_var($url, FILTER_SANITIZE_URL);
}
?>

<main class="wpr-page">
    <div class="container wpr-container">
        <header class="header">
            <h1>WINNER PHOTO RELEASE FORM</h1>
            <p class="wpr-subtitle">
                Congratulations on your win! Please complete this form to give us permission to share your joy with our community.
            </p>
        </header>

        <div class="search-form wpr-form-wrap">
            <?php if (isset($_SESSION['flash_form_success'])): ?>
                <div class="success-message mb-3">
                    <?php echo htmlspecialchars($_SESSION['flash_form_success']); ?>
                </div>
                <?php unset($_SESSION['flash_form_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_form_error'])): ?>
                <div class="error-message mb-3">
                    <?php echo htmlspecialchars($_SESSION['flash_form_error']); ?>
                </div>
                <?php unset($_SESSION['flash_form_error']); ?>
            <?php endif; ?>
            <?php if (!empty($winners)) : ?>
            <div class="winners-section">
                <h2>Real winners from our community</h2>
                <div class="winners-gallery">
                    <?php foreach ($winners as $winner) : ?>
                        <div class="gallery-item">
                            <a href="<?php echo esc_url('/wordpress/'.$winner['winner_photo_path']); ?>" class="glightbox">
                                <img src="<?php echo esc_url('/wordpress/'.$winner['winner_photo_path']); ?>" alt="Winner Photo">
                            </a>
                            <div class="winner-info">
                                <h4><?php echo esc_html($winner['first_name']); ?></h4>
                                <p><?php echo esc_html($winner['city'] . ', ' . $winner['province']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <form id="winner-photo-release-form" class="wpr-form" method="post" action="controllers/winnerphotoreleasecontroller.php" enctype="multipart/form-data">
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="wpr-grid two-col">
                        <div class="wpr-field">
                            <label for="first_name">First Name <span>*</span></label>
                            <input class="form-input" type="text" id="first_name" name="first_name" required>
                        </div>

                        <div class="wpr-field">
                            <label for="last_name">Last Name <span></span></label>
                            <input class="form-input" type="text" id="last_name" name="last_name">
                        </div>

                        <div class="wpr-field">
                            <label for="email">Email Address <span>*</span></label>
                            <input class="form-input" type="email" id="email" name="email" required>
                        </div>

                        <div class="wpr-field">
                            <label for="phone">Phone Number <span>*</span></label>
                            <input class="form-input" type="tel" id="phone" name="phone" placeholder="(123) 456-7890" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Address Information</h3>
                    <p class="wpr-note">
                        Your address and phone number are collected for internal records only and will not be displayed publicly.
                    </p>

                    <div class="wpr-grid two-col">
                        <div class="wpr-field full">
                            <label for="address_1">Address Line 1 <span>*</span></label>
                            <input class="form-input" type="text" id="address_1" name="address_1" required>
                        </div>

                        

                        <div class="wpr-field">
                            <label for="city">City <span>*</span></label>
                            <input class="form-input" type="text" id="city" name="city" required>
                        </div>

                        <div class="wpr-field">
                            <label for="province">Province / Territory <span>*</span></label>
                            <select class="form-input" id="province" name="province" required>
                                <option value="" disabled selected>Select your province</option>
                                <option value="AB">Alberta</option>
                                <option value="BC">British Columbia</option>
                                <option value="MB">Manitoba</option>
                                <option value="NB">New Brunswick</option>
                                <option value="NL">Newfoundland and Labrador</option>
                                <option value="NS">Nova Scotia</option>
                                <option value="ON">Ontario</option>
                                <option value="PE">Prince Edward Island</option>
                                <option value="QC">Quebec</option>
                                <option value="SK">Saskatchewan</option>
                                <option value="NT">Northwest Territories</option>
                                <option value="NU">Nunavut</option>
                                <option value="YT">Yukon</option>
                            </select>
                        </div>
                        <div class="wpr-field">
                            <label for="postal_code">Postal Code <span></span></label>
                            <input class="form-input" type="text" id="postal_code" name="postal_code" placeholder="">
                        </div>
                        <div class="wpr-field">
                            <label for="address_2">Suite / Unit # (Optional)</label>
                            <input class="form-input" type="text" id="address_2" name="address_2">
                        </div>
                        
                    </div>
                </div>

                <div class="form-section">
                    <h3>Upload Your Photo</h3>

                    <div class="wpr-field">
                        <label for="winner_photo">Winner Photo <span>*</span></label>
                        <label for="winner_photo" class="wpr-upload-box" id="upload-box">
                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                            <strong>Click to upload or drag and drop</strong>
                            <span>PNG, JPG or JPEG (MAX. 10MB)</span>
                            <em id="upload-filename">No file selected</em>
                        </label>
                        <input type="file" id="winner_photo" name="winner_photo" accept=".png,.jpg,.jpeg" required>
                        <p id="upload-error" class="wpr-upload-error" aria-live="polite"></p>
                        <img id="upload-preview" class="wpr-upload-preview" alt="Uploaded winner photo preview">
                    </div>
                </div>

                <div class="form-section">
                    <h3>Terms and Conditions</h3>
                    <div class="wpr-terms">
                        <label class="wpr-check">
                            <input type="checkbox" id="agree_terms" name="agree_terms" required>
                            <span>I agree to the photo use terms. <b>*</b></span>
                        </label>
                        <p>
                            By submitting this form, you agree that Baby Brands Gift Club / Samplits may use your first name,
                            last initial, city, and province, and may use your approved photo on their website and social media
                            as a monthly winner.
                        </p>
                         <!-- FULL TERMS BOX -->
                        <div class="terms-box" id="termsBox" style="display:none;">
                            <p>Full Terms and Conditions:</p>
                            <p>
                                By checking this box and submitting this form, I grant Baby Brands Gift Club / Samplits and its affiliates the perpetual, royalty-free, worldwide right to use, reproduce, modify, publish, and distribute the submitted photograph(s) in any media format, including but not limited to websites, social media platforms, marketing materials, and promotional content.
                            </p>
                            <p>
                                I understand that my first name, last initial, city, and province may be displayed alongside my photograph. I acknowledge that my full address and telephone number will be kept confidential and used solely for internal record-keeping purposes.
                            </p>
                            <p>
                                I confirm that I am the rightful owner of the photograph or have obtained necessary permissions from the copyright holder. I waive any right to inspect or approve the finished product or any promotional materials in which the photograph may appear.
                            </p>
                            <p>
                                I release Baby Brands Gift Club / Samplits from any claims, liabilities, or damages arising from the use of my photograph and information as described in these terms.
                            </p>
                        </div>
                        <a href="javascript:void(0)" class="toggle-terms" onclick="toggleTerms()">Hide full terms</a>
                        <div class="wpr-privacy">
                            <strong>Privacy Notice:</strong>
                            Your address and phone number are collected for internal records only and will not be displayed publicly.
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-generate wpr-submit-btn">Submit Photo Release Form</button>
                </div>
            </form>
        </div>

        <p class="wpr-contact">
            Questions? Contact us at
            <a href="mailto:support@babybrandsgiftclub.com">support@babybrandsgiftclub.com</a>
        </p>
    </div>
</main>
<script>
const lightbox = GLightbox({
    selector: '.glightbox'
});
function toggleTerms() {
    const box = document.getElementById("termsBox");
    const link = document.querySelector(".toggle-terms");

    if (box.style.display === "none") {
        box.style.display = "block";
        link.textContent = "Hide full terms";
    } else {
        box.style.display = "none";
        link.textContent = "View full terms";
    }
}
</script>
<script src="public/winner-photo-release.js?v=<?php echo filemtime(__DIR__ . '/../public/winner-photo-release.js'); ?>"></script>
