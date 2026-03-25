<!-- Include Pregnancy Calculator Specific Styles -->
<link rel="stylesheet" href="https://www.babybrandsgiftclub.com/nameGeneratorTool/public/pregnancy-calculator.css">
<?php include '../template/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-container">
        <div class="hero-text">
            <h1>Discover Your Expected Due Date</h1>
            <p>Start planning for the big day with our accurate pregnancy due date calculator. Get personalized pregnancy timeline and important milestones.</p>
            <div class="hero-stats">
                <div class="stat">
                    <i class="fas fa-calendar-check"></i>
                    <span class="number">280</span>
                    <span class="label">Days Average Pregnancy</span>
                </div>
                <div class="stat">
                    <i class="fas fa-baby"></i>
                    <span class="number">40</span>
                    <span class="label">Weeks Full Term</span>
                </div>
                <div class="stat">
                    <i class="fas fa-heart-pulse"></i>
                    <span class="number">3</span>
                    <span class="label">Trimesters Pregnancy Stages</span>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
</section>

<!-- Calculator Section -->
<div class="calculator-wrapper">
    <div class="calculator-container" id="calculator">
        <div class="calc-header">
            <h2>Calculate Your Due Date</h2>
            <p>Choose your preferred calculation method below</p>
        </div>

        <div class="status-banner">
            <i class="fas fa-check-circle"></i>
            This is not a diagnosis. The calculations provided are approximate estimates based on average values.
        </div>

        <div class="method-tabs">
            <button type="button" class="tab-btn active" onclick="switchTab('lmp')">Last Menstrual Period</button>
            <button type="button" class="tab-btn" onclick="switchTab('conception')">Conception Date</button>
            <button type="button" class="tab-btn" onclick="switchTab('ivf')">IVF Transfer</button>
        </div>

        <div id="error-message" class="error-message">
            <span id="error-text"></span>
        </div>

        <form id="calculator-form" onsubmit="event.preventDefault();calculateDueDate();">
            <div class="method-content active" id="lmp-content">
                <div class="form-group">
                    <label for="lmp-date">First Day of Last Menstrual Period</label>
                    <input type="date" id="lmp-date" name="lmp_date">
                </div>
                <div class="form-group">
                    <label for="cycle-length">Average Cycle Length (days)</label>
                    <select id="cycle-length" name="cycle_length">
                        <option value="28">28 days (Standard)</option>
                        <option value="21">21 days</option>
                        <option value="24">24 days</option>
                        <option value="26">26 days</option>
                        <option value="30">30 days</option>
                        <option value="32">32 days</option>
                        <option value="35">35 days</option>
                    </select>
                </div>
            </div>

            <div class="method-content" id="conception-content">
                <div class="form-group">
                    <label for="conception-date">Conception Date</label>
                    <input type="date" id="conception-date" name="conception_date">
                </div>
            </div>

            <div class="method-content" id="ivf-content">
                <div class="form-group">
                    <label for="ivf-date">IVF Transfer Date</label>
                    <input type="date" id="ivf-date" name="ivf_date">
                </div>
                <div class="form-group">
                    <label for="transfer-day">Transfer Day</label>
                    <select id="transfer-day" name="transfer_day">
                        <option value="3">Day 3 Transfer</option>
                        <option value="5">Day 5 Transfer (Blastocyst)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="calculate-btn">
                <i class="fas fa-calculator"></i>
                Calculate Due Date
            </button>
        </form>

        <div id="results" class="results-section">
            <div class="main-result">
                <h4><i class="fas fa-baby"></i> Your Estimated Due Date</h4>
                <div class="due-date" id="due-date"></div>
                <div class="countdown" id="countdown"></div>
            </div>

            <div class="result-grid">
                <div class="result-card">
                    <h5>Current Week</h5>
                    <div class="result-value" id="current-week"></div>
                </div>
                <div class="result-card">
                    <h5>Trimester</h5>
                    <div class="result-value" id="trimester"></div>
                </div>
                <div class="result-card">
                    <h5>Days Remaining</h5>
                    <div class="result-value" id="days-remaining"></div>
                </div>
                <div class="result-card">
                    <h5>Conception Date</h5>
                    <div class="result-value" id="conception-result"></div>
                </div>
            </div>

            <div class="pregnancy-timeline">
                <div class="progress-bar" id="progress-bar"></div>
                <div class="timeline-labels">
                    <span>0 weeks</span>
                    <span>12 weeks</span>
                    <span>26 weeks</span>
                    <span>40 weeks</span>
                </div>
            </div>

            <button class="reset-btn" type="button" onclick="resetCalculator()">
                <i class="fas fa-refresh"></i>
                Calculate Again
            </button>
        </div>
    </div>
</div>

<!-- Info Cards Section -->
<section class="info-section" id="about">
    <div class="info-container">
        <div class="info-header">
            <h2>How Our Calculator Works</h2>
            <p>Understanding pregnancy due date calculations</p>
        </div>
        <div class="info-cards">
            <div class="info-card">
                <div class="card-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="card-content">
                    <h3>Last Menstrual Period (LMP)</h3>
                    <p>The most common method adds 280 days (40 weeks) to the first day of your last menstrual period. This assumes a 28-day cycle with ovulation on day 14. We adjust for different cycle lengths.</p>
                </div>
            </div>
            <div class="info-card">
                <div class="card-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="card-content">
                    <h3>Conception Date</h3>
                    <p>If you know when conception occurred, we add 266 days (38 weeks) to estimate your due date. This method is more accurate for women who track ovulation or have irregular cycles.</p>
                </div>
            </div>
            <div class="info-card">
                <div class="card-icon">
                    <i class="fas fa-microscope"></i>
                </div>
                <div class="card-content">
                    <h3>IVF Transfer Date</h3>
                    <p>For IVF pregnancies, we calculate based on the transfer date: Day 3 transfer + 263 days, or Day 5 transfer + 261 days for the most accurate results.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pregnancy Milestones Section -->
<section class="hero-section-alt bg-gradient-1" id="milestones">
    <div class="info-container">
        <div class="info-header">
            <h2>Important Pregnancy Milestones</h2>
            <p>Track your baby's development week by week throughout your pregnancy journey</p>
        </div>
        <div class="milestone-grid">
            <div class="milestone-item">
                <span class="milestone-week">Week 4-6</span>
                <h4>Heartbeat Begins</h4>
                <p>Your baby's heart starts beating around week 5-6. This tiny heartbeat can be detected via ultrasound, marking an exciting first milestone.</p>
            </div>
            <div class="milestone-item">
                <span class="milestone-week">Week 12</span>
                <h4>End of First Trimester</h4>
                <p>All major organs have formed. The risk of miscarriage drops significantly. Many parents choose to announce their pregnancy at this stage.</p>
            </div>
            <div class="milestone-item">
                <span class="milestone-week">Week 16-20</span>
                <h4>Feel Baby Move</h4>
                <p>First movements (quickening) are felt, usually between 16-22 weeks. These gentle flutters soon become recognizable kicks and rolls.</p>
            </div>
            <div class="milestone-item">
                <span class="milestone-week">Week 20</span>
                <h4>Anatomy Scan</h4>
                <p>Detailed mid-pregnancy ultrasound checks baby's development. Gender can often be revealed if parents wish to know.</p>
            </div>
            <div class="milestone-item">
                <span class="milestone-week">Week 28</span>
                <h4>Third Trimester</h4>
                <p>Final stretch begins! Baby's brain develops rapidly, and they start to regulate their own body temperature.</p>
            </div>
            <div class="milestone-item">
                <span class="milestone-week">Week 37-40</span>
                <h4>Full Term</h4>
                <p>Baby is fully developed and ready for birth. Most babies are born between 37-42 weeks. The big day is near!</p>
            </div>
        </div>
    </div>
</section>

<!-- Prenatal Care Tips Section -->
<section class="hero-section-alt bg-gradient-2" id="tips">
    <div class="info-container">
        <div class="info-header">
            <h2>Prenatal Care & Health Tips</h2>
            <p>Essential guidance for a healthy pregnancy journey for both mom and baby</p>
        </div>
        <div class="tips-grid">
            <div class="tip-box">
                <h4><i class="fas fa-heartbeat"></i> Physical Health</h4>
                <ul>
                    <li>Take prenatal vitamins with folic acid daily</li>
                    <li>Stay hydrated - drink 8-10 glasses of water</li>
                    <li>Exercise regularly with doctor's approval</li>
                    <li>Get 7-9 hours of sleep each night</li>
                    <li>Attend all scheduled prenatal appointments</li>
                    <li>Monitor weight gain according to guidelines</li>
                </ul>
            </div>
            <div class="tip-box">
                <h4><i class="fas fa-apple-alt"></i> Nutrition & Diet</h4>
                <ul>
                    <li>Eat balanced meals with proteins and vegetables</li>
                    <li>Include iron-rich foods and calcium sources</li>
                    <li>Avoid raw fish, meat, and unpasteurized dairy</li>
                    <li>Limit caffeine to 200mg per day</li>
                    <li>Take omega-3 fatty acids for brain development</li>
                    <li>Eat small, frequent meals to ease nausea</li>
                </ul>
            </div>
            <div class="tip-box">
                <h4><i class="fas fa-shield-alt"></i> Safety First</h4>
                <ul>
                    <li>Avoid alcohol, tobacco, and recreational drugs</li>
                    <li>Check medications with your doctor first</li>
                    <li>Wear seatbelts properly during pregnancy</li>
                    <li>Avoid hot tubs and saunas</li>
                    <li>Be cautious with household chemicals</li>
                    <li>Report any unusual symptoms immediately</li>
                </ul>
            </div>
            <div class="tip-box">
                <h4><i class="fas fa-brain"></i> Mental Wellness</h4>
                <ul>
                    <li>Practice stress-reduction techniques daily</li>
                    <li>Join pregnancy support groups if helpful</li>
                    <li>Communicate openly with your partner</li>
                    <li>Consider prenatal yoga or meditation</li>
                    <li>Seek help for anxiety or depression</li>
                    <li>Prepare mentally for labor and parenting</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Trimester Guide Section -->
<section class="hero-section-alt bg-gradient-3">
    <div class="info-container">
        <div class="info-header">
            <h2>What to Expect by Trimester</h2>
            <p>Your complete guide to changes and developments in each pregnancy stage</p>
        </div>
        <div class="trimester-cards">
            <div class="trimester-card">
                <div class="trimester-number">1st</div>
                <h4>First Trimester</h4>
                <div class="trimester-weeks">Weeks 1-12</div>
                <p><strong>Your Body:</strong> Morning sickness, fatigue, breast tenderness, and frequent urination are common.</p>
                <p><strong>Your Baby:</strong> All major organs form. By week 12, baby is about 2 inches long with a beating heart.</p>
                <p><strong>To Do:</strong> Start prenatal vitamins, schedule first doctor visit, and avoid harmful substances.</p>
            </div>
            <div class="trimester-card">
                <div class="trimester-number">2nd</div>
                <h4>Second Trimester</h4>
                <div class="trimester-weeks">Weeks 13-26</div>
                <p><strong>Your Body:</strong> Energy returns! Baby bump grows, and you'll feel first movements around week 18-22.</p>
                <p><strong>Your Baby:</strong> Rapid growth phase. Can hear sounds, make facial expressions, and develop sleep patterns.</p>
                <p><strong>To Do:</strong> Have anatomy scan, consider childbirth classes, and start thinking about baby names.</p>
            </div>
            <div class="trimester-card">
                <div class="trimester-number">3rd</div>
                <h4>Third Trimester</h4>
                <div class="trimester-weeks">Weeks 27-40+</div>
                <p><strong>Your Body:</strong> Shortness of breath, back pain, and frequent bathroom trips. Baby's kicks become stronger.</p>
                <p><strong>Your Baby:</strong> Lungs mature, weight gain accelerates, and baby positions for birth. Fully developed by week 37.</p>
                <p><strong>To Do:</strong> Pack hospital bag, finalize birth plan, install car seat, and prepare nursery.</p>
            </div>
        </div>
    </div>
</section>
<?php include '../template/footer.php'; ?>

<!-- Include Pregnancy Calculator JavaScript -->
<script src="https://www.babybrandsgiftclub.com/nameGeneratorTool/public/pregnancy-calculator.js"></script>
