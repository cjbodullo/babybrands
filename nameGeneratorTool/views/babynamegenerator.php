<main>
  <div class="container">
      <header class="header">
          <h1>
              <span style="color: #4ecdc4; font-weight: 1100;">FIND</span> 
              <span style="color: #1a5a58; font-weight: 1100;">THE PERFECT BABY NAME</span> 
              <span style="color: #4ecdc4; font-weight: 1100;">FOR YOUR LITTLE ONE</span>
          </h1>
          <p style="color: #096768 ; font-weight: 600; line-height: 1.7; margin-bottom: 14px;">
              Looking for a name as special as your baby? Let our Baby Name Generator help you find the perfect one, whether you're expecting a baby boy, girl, or keeping the gender a surprise!
          </p>
          <p style="color: #66CCCC; font-weight: 400; line-height: 1.8; margin-bottom: 0;">
              Choosing the right name for your baby can be a fun yet tricky task. With our simple and intuitive tool, you can search for names based on the gender, origin, or even the first letter. Start by selecting your preferred category, and once you find a few names that resonate with you, add them to your profile. As you narrow it down, revisit your list and let your heart decide on that perfect fit for your bundle of joy.
          </p>
      </header>

      <div class="search-form">
          <form id="babyNameForm">
              <!-- Gender Selection -->
              <div class="form-section">
                  <h3>SEARCH NAME FOR:</h3>
                  <div class="segmented" role="tablist" aria-label="Search name for">
                      <label class="seg-option">
                          <input type="radio" name="gender" value="all" checked>
                          <span class="seg-pill">ALL</span>
                      </label>
                      <label class="seg-option">
                          <input type="radio" name="gender" value="boy">
                          <span class="seg-pill">FOR HIM</span>
                      </label>
                      <label class="seg-option">
                          <input type="radio" name="gender" value="girl">
                          <span class="seg-pill">FOR HER</span>
                      </label>
                      <label class="seg-option">
                          <input type="radio" name="gender" value="neutral">
                          <span class="seg-pill">NEUTRAL</span>
                      </label>
                  </div>
              </div>

              <!-- First Letter Selection -->
              <div class="form-section">
                  <h3>SEARCH NAME BY FIRST LETTER:</h3>
                  <div class="alphabet-grid">
                      <?php foreach(range('A', 'Z') as $letter): ?>
                          <label class="alphabet-option">
                              <input type="checkbox" name="firstLetter" value="<?php echo $letter; ?>">
                              <span class="alphabet-custom"><?php echo $letter; ?></span>
                          </label>
                      <?php endforeach; ?>
                  </div>
              </div>

              <!-- Explore By Category -->
              <div class="form-section">
                  <h3>EXPLORE BY:</h3>
                  <div class="dropdown-group">
                      <select name="origin" class="form-select">
                          <option value="">Origin</option>
                            <option value="any">any</option>
                            <option value="African">African</option>
                            <option value="African American">African American</option>
                            <option value="American">American</option>
                            <option value="American Indian">American Indian</option>
                            <option value="British">British</option>
                            <option value="Arabic">Arabic</option>
                            <option value="Aramaic">Aramaic</option>
                            <option value="Armenian">Armenian</option>
                            <option value="Basque">Basque</option>
                            <option value="Celtic">Celtic</option>
                            <option value="Chechen">Chechen</option>
                            <option value="Chinese">Chinese</option>
                            <option value="Dutch">Dutch</option>
                            <option value="Egyptian">Egyptian</option>
                            <option value="English">English</option>
                            <option value="Eritrean">Eritrean</option>
                            <option value="Filipino">Filipino</option>
                            <option value="French">French</option>
                            <option value="German">German</option>
                            <option value="Ghanaian">Ghanaian</option>
                            <option value="Greek">Greek</option>
                            <option value="Hawaiian">Hawaiian</option>
                            <option value="Hebrew">Hebrew</option>
                            <option value="Hindi">Hindi</option>
                            <option value="Hungarian">Hungarian</option>
                            <option value="Indian">Indian</option>
                            <option value="Irish">Irish</option>
                            <option value="Italian">Italian</option>
                            <option value="Japanese">Japanese</option>
                            <option value="Korean">Korean</option>
                            <option value="Latin">Latin</option>
                            <option value="Maori">Maori</option>
                            <option value="Muslim">Muslim</option>
                            <option value="Native American">Native American</option>
                            <option value="Nigerian">Nigerian</option>
                            <option value="Persian">Persian</option>
                            <option value="Polish">Polish</option>
                            <option value="Polynesian">Polynesian</option>
                            <option value="Punjabi">Punjabi</option>
                            <option value="Russian">Russian</option>
                            <option value="Sanskrit">Sanskrit</option>
                            <option value="Scandinavian">Scandinavian</option>
                            <option value="Scottish">Scottish</option>
                            <option value="Slavic">Slavic</option>
                            <option value="Spanish">Spanish</option>
                            <option value="Swahili">Swahili</option>
                            <option value="Swedish">Swedish</option>
                            <option value="Teutonic">Teutonic</option>
                            <option value="Tongan">Tongan</option>
                            <option value="Turkish">Turkish</option>
                            <option value="Ugandan">Ugandan</option>
                            <option value="Vietnamese">Vietnamese</option>
                            <option value="Welsh">Welsh</option>
                            <option value="Yoruban">Yoruban</option>

                      </select>

                      <select name="style" class="form-select">
                          <option value="">Style</option>
                            <option value="Classic">Classic</option>
                            <option value="Modern">Modern</option>
                            <option value="Popular">Popular</option>
                            <option value="Short">Short</option>
                            <option value="Uncommon">Uncommon</option>
                            <option value="Edgy">Edgy</option>
                            <option value="Religious and Spiritual">Religious and Spiritual</option>
                            <option value="Earthy">Earthy</option>
                            <option value="Famous and Historical">Famous and Historical</option>
                            <option value="Fictional">Fictional</option>
                            <option value="Seasonal">Seasonal</option>

                      </select>

                      <select name="meaning" class="form-select">
                          <option value="">Meaning</option>
                          <option value="strength">Strength</option>
                          <option value="beauty">Beauty</option>
                          <option value="wisdom">Wisdom</option>
                          <option value="love">Love</option>
                          <option value="peace">Peace</option>
                          <option value="joy">Joy</option>
                          <option value="hope">Hope</option>
                          <option value="brave">Brave</option>
                          <option value="light">Light</option>
                          <option value="noble">Noble</option>
                          <option value="nature">Nature</option>
                          <option value="water">Water</option>
                          <option value="fire">Fire</option>
                          <option value="earth">Earth</option>
                          <option value="star">Star</option>
                          <option value="moon">Moon</option>
                          <option value="sun">Sun</option>
                          <option value="gift">Gift</option>
                          <option value="blessing">Blessing</option>
                          <option value="victory">Victory</option>
                      </select>

                      <select name="syllables" class="form-select">
                          <option value=""># Syllables</option>
                          <option value="1">1 Syllable</option>
                          <option value="2">2 Syllables</option>
                          <option value="3">3 Syllables</option>
                          <option value="4">4+ Syllables</option>
                      </select>
                  </div>
              </div>

              <!-- Action Buttons -->
              <div class="button-group">
                  <button type="submit" class="btn-generate">GENERATE</button>
                  <button type="reset" class="btn-reset">RESET</button>
              </div>

              <!-- OR Divider -->
              <div class="or-divider">
                  <span>OR</span>
              </div>

              <!-- Search By Name -->
              <div class="form-section">
                  <h3>SEARCH BY NAME:</h3>
                  <input type="text" name="customName" placeholder="Enter the name" class="form-input">
              </div>
          </form>
      </div>

      <!-- Results Section -->
      <div id="resultsSection" class="results-section" style="display: none;">
          <h3>GENERATED BABY NAMES</h3>
          <div id="loadingSpinner" class="loading" style="display: none;">
              <div class="spinner"></div>
              <p>Generating perfect names for your little one...</p>
          </div>
          <div id="nameResults" class="name-results">
              <!-- Names will be populated here -->
          </div>
          
          <div id="bottomActions" class="bottom-actions" style="display: none;">
              <button id="showMoreBtn" class="btn-show-more">SHOW MORE</button>
              <button id="resetBtn" class="btn-reset-bottom">RESET</button>
          </div>
      </div>
  </div>
</main>

