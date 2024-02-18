document.addEventListener('DOMContentLoaded', function () {
    const addGuestModalBtn = document.getElementById('addGuestModalBtn');
    const closeAddGuestModalBtn = document.getElementById('closeAddGuestModalBtn');
    const addGuestModal = document.getElementById('addGuestModal');
  
    addGuestModalBtn.addEventListener('click', function () {
      addGuestModal.classList.remove('hidden');
    });
  
    closeAddGuestModalBtn.addEventListener('click', function () {
      addGuestModal.classList.add('hidden');
    });
  
    // Close modal when clicking outside the modal content
    document.addEventListener('click', function (event) {
      if (event.target === addGuestModal) {
          addGuestModal.classList.add('hidden');
      }
    });
  });
  