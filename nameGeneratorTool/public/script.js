// Baby Name Generator - With Duplicate Prevention
console.log('JavaScript loaded successfully');

// Global variables with duplicate tracking
let currentFormData = null;
let displayedNames = [];
let allGeneratedNames = []; // Track all names ever generated

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing form...');
    
    // Get DOM elements
    const form = document.getElementById('babyNameForm');
    const resultsSection = document.getElementById('resultsSection');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const nameResults = document.getElementById('nameResults');
    const bottomActions = document.getElementById('bottomActions');
    const showMoreBtn = document.getElementById('showMoreBtn');
    const resetBtn = document.getElementById('resetBtn');
    
    if (!form) {
        console.error('ERROR: Form not found!');
        return;
    }
    
    // Form submit event - Generate 8 names
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted - generating 8 unique names');
        
        // Collect form data
        const formData = new FormData(form);
        currentFormData = {
            gender: formData.get('gender') || 'all',
            firstLetters: formData.getAll('firstLetter'),
            origin: formData.get('origin') || '',
            meaning: formData.get('meaning') || '',
            style: formData.get('style') || '',
            customName: formData.get('customName') || '',
            count: 8,
            existingNames: [] // Start with empty list
        };
        
        // Validate
        const hasData = currentFormData.firstLetters.length > 0 || 
                       currentFormData.origin || currentFormData.meaning || 
                       currentFormData.style || currentFormData.customName;
        
        if (!hasData) {
            showError('Please select at least one search criteria.');
            return;
        }
        
        // Reset arrays for new search
        displayedNames = [];
        allGeneratedNames = [];
        showLoading();
        generateNames(true); // true = initial load
    });
    
    // Reset form handler
    form.addEventListener('reset', function() {
        resetEverything();
    });
    
    // Show More button - Generate 5 more unique names
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function() {
            console.log('Show More clicked - generating 5 more unique names');
            showMoreBtn.disabled = true;
            showMoreBtn.textContent = 'LOADING...';
            
            // Update form data with existing names to avoid duplicates
            currentFormData.count = 5;
            currentFormData.existingNames = allGeneratedNames.map(name => name.name);
            
            console.log('Excluding these names:', currentFormData.existingNames);
            generateNames(false); // false = load more
        });
    }
    
    // Bottom Reset button
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            resetEverything();
        });
    }
    
    // Generate names from API
    async function generateNames(isInitialLoad = true) {
        try {
            console.log(`Calling API for ${currentFormData.count} unique names...`);
            
            const response = await fetch('./controllers/babynamegeneratorcontroller.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(currentFormData)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            console.log('API response:', result);
            
            hideLoading();
            
            if (result.success && result.names) {
                // Filter out any duplicates that might still slip through
                const uniqueNames = filterDuplicates(result.names);
                console.log(`Received ${result.names.length} names, ${uniqueNames.length} are unique`);
                
                if (uniqueNames.length === 0) {
                    showError('No new unique names found. Try different criteria or reset.');
                    if (showMoreBtn) {
                        showMoreBtn.disabled = false;
                        showMoreBtn.textContent = 'SHOW MORE';
                    }
                    return;
                }
                
                if (isInitialLoad) {
                    // Show initial names
                    displayInitialNames(uniqueNames);
                } else {
                    // Add more names to existing list
                    addMoreNames(uniqueNames);
                }
                
                // Add to our global tracking
                allGeneratedNames = [...allGeneratedNames, ...uniqueNames];
            } else {
                showError(result.error || 'Failed to generate names');
            }
            
        } catch (error) {
            console.error('Error:', error);
            hideLoading();
            showError('Network error. Please check console for details.');
            
            // Re-enable show more button on error
            if (showMoreBtn) {
                showMoreBtn.disabled = false;
                showMoreBtn.textContent = 'SHOW MORE';
            }
        }
    }
    
    // Filter out duplicate names
    function filterDuplicates(newNames) {
        const existingNamesList = allGeneratedNames.map(name => name.name.toLowerCase());
        
        return newNames.filter(name => {
            const nameLower = name.name.toLowerCase();
            return !existingNamesList.includes(nameLower);
        });
    }
    
    // Display initial names (create new list)
        // Display initial names (create new list)
        function displayInitialNames(names) {
            if (!nameResults || !names || names.length === 0) {
                showError('No names found. Try different criteria.');
                return;
            }

            console.log(`Displaying ${names.length} initial names`);
            displayedNames = [...names];
            
            // Create new list
            nameResults.innerHTML = '<div class="name-list"></div>';
            const container = nameResults.querySelector('.name-list');
            
            names.forEach((name, index) => {
                const item = createNameListItem(name, index);
                container.appendChild(item);
                
                // ✅ Auto-expand the FIRST name card only
                if (index === 0) {
                    // Create and show details for first name immediately
                    const details = document.createElement('div');
                    details.className = 'name-details';
                    details.innerHTML = `
                        <div class="name-details-header">
                            <span>Origin: <strong>${name.origin || 'Unknown'}</strong></span>
                            <span>Meaning: <strong>"${name.meaning || 'No meaning available'}"</strong></span>
                            <span>Popularity: <strong>${Math.floor(Math.random() * 10) + 1}</strong></span>
                        </div>
                        <div class="name-description">
                            ${name.description || 'A beautiful name with rich cultural heritage.'}
                        </div>
                    `;
                    item.parentNode.insertBefore(details, item.nextSibling);
                }
            });
            
            // Show results and bottom buttons
            if (resultsSection) {
                resultsSection.style.display = 'block';
                resultsSection.scrollIntoView({ behavior: 'smooth' });
            }
            if (bottomActions) {
                bottomActions.style.display = 'flex';
            }
        }

    
    // Add more names to existing list
    function addMoreNames(names) {
        const container = nameResults.querySelector('.name-list');
        if (!container || !names || names.length === 0) {
            console.log('No new names to add');
            return;
        }
        
        console.log(`Adding ${names.length} more unique names`);
        
        names.forEach((name, index) => {
            const item = createNameListItem(name, displayedNames.length + index);
            container.appendChild(item);
        });
        
        displayedNames = [...displayedNames, ...names];
        
        // Re-enable show more button
        if (showMoreBtn) {
            showMoreBtn.disabled = false;
            showMoreBtn.textContent = 'SHOW MORE';
        }
        
        // Scroll to new names
        setTimeout(() => {
            const newItems = container.children;
            if (newItems.length > 0) {
                const lastItem = newItems[newItems.length - 1];
                lastItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 100);
        
        console.log(`Total displayed names: ${displayedNames.length}`);
        console.log(`Total generated names: ${allGeneratedNames.length}`);
    }
    
    // Create name list item
    function createNameListItem(name, index) {
        const listItem = document.createElement('div');
        listItem.className = 'name-item';
        listItem.innerHTML = `
            <h4 class="name-title">${name.name || 'Unknown Name'}</h4>
            <div class="name-actions">
                <div class="action-icon heart-icon" title="Add to favorites"></div>
                <div class="action-icon plus-icon" title="Save name"></div>
            </div>
        `;
        
        // Add click event for details
        listItem.addEventListener('click', function(e) {
            if (!e.target.classList.contains('action-icon')) {
                toggleNameDetails(name, listItem, index);
            }
        });

        // Add action icon events
        const heartIcon = listItem.querySelector('.heart-icon');
        const plusIcon = listItem.querySelector('.plus-icon');

        heartIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            heartIcon.classList.toggle('active');
        });

        plusIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            plusIcon.classList.toggle('active');
        });
        
        return listItem;
    }
    
    // Toggle name details - NO INDIVIDUAL SEE MORE BUTTON
    function toggleNameDetails(name, listItem, index) {
        const existingDetails = listItem.nextElementSibling;
        
        if (existingDetails && existingDetails.classList.contains('name-details')) {
            existingDetails.remove();
        } else {
            document.querySelectorAll('.name-details').forEach(d => d.remove());
            
            const details = document.createElement('div');
            details.className = 'name-details';
            details.innerHTML = `
                <div class="name-details-header">
                    <span>Origin: <strong>${name.origin || 'Unknown'}</strong></span>
                    <span>Meaning: <strong>"${name.meaning || 'No meaning available'}"</strong></span>
                    <span>Popularity: <strong>${Math.floor(Math.random() * 10) + 1}</strong></span>
                </div>
                <div class="name-description">
                    ${name.description || 'A beautiful name with rich cultural heritage.'}
                </div>
            `;
            
            listItem.parentNode.insertBefore(details, listItem.nextSibling);
        }
    }
    
    // Reset everything
    function resetEverything() {
        console.log('Resetting everything...');
        displayedNames = [];
        allGeneratedNames = [];
        currentFormData = null;
        hideResults();
        form.reset();
    }
    
    // Helper functions
    function showLoading() {
        if (resultsSection) resultsSection.style.display = 'block';
        if (loadingSpinner) loadingSpinner.style.display = 'block';
        if (nameResults) nameResults.innerHTML = '';
        if (bottomActions) bottomActions.style.display = 'none';
    }
    
    function hideLoading() {
        if (loadingSpinner) loadingSpinner.style.display = 'none';
    }
    
    function hideResults() {
        if (resultsSection) resultsSection.style.display = 'none';
        if (nameResults) nameResults.innerHTML = '';
        if (bottomActions) bottomActions.style.display = 'none';
    }
    
    function showError(message) {
        hideLoading();
        if (nameResults) {
            nameResults.innerHTML = `<div class="error-message">${message}</div>`;
        }
        if (resultsSection) resultsSection.style.display = 'block';
        if (bottomActions) bottomActions.style.display = 'none';
    }
    
    // Add alphabet animation
    document.querySelectorAll('.alphabet-option').forEach(option => {
        option.addEventListener('click', function() {
            const span = this.querySelector('.alphabet-custom');
            if (span) {
                span.style.transform = 'scale(0.95)';
                setTimeout(() => span.style.transform = '', 150);
            }
        });
    });
    
    console.log('Baby Name Generator with Duplicate Prevention initialized successfully');
});
