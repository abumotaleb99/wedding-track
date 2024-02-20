@extends('layout.app')
@section('content')

<div class="bg-[#fff] w-full lg:max-w-7xl mx-auto px-4 py-2">
  <div class="overflow-x-auto">
    <div class="inline-block min-w-full rounded-md overflow-hidden">
      <h2 class="text-2xl text-[#090B10] font-montserrat font-bold py-3">Check-In List</h2>
      <table class="min-w-full" id="myTable">
        <thead>
          <tr class="border">
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">ID</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Guest ID</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Name</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Company Name</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Gender</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Entry Time</th>
          </tr>
        </thead>
        <tbody id="checkInTableBody">
          <!-- Table rows will be dynamically populated here -->
        </tbody>
      </table>
    </div>
  </div>
</div>

@push('script')
<script>
  function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = date.getHours() % 12 || 12; // Convert to 12-hour format
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const period = date.getHours() >= 12 ? 'PM' : 'AM';

    return `${year}-${month}-${day} - ${hours}:${minutes} ${period}`;
}


  async function getCheckInList() {
      try {
          var baseUrl = '{{ config('app.url') }}';
          const response = await fetch(baseUrl+"/check-in/list");
          if (!response.ok) {
              throw new Error("Failed to fetch check-in list.");
          }
          const data = await response.json();
          populateTable(data.data);
      } catch (error) {
          console.error(error);
      }
  }

  function populateTable(data) {
      const tableBody = document.getElementById("checkInTableBody");
      tableBody.innerHTML = ""; // Clear existing rows
      data.forEach((item, index) => {
          const formattedDateTime = formatDateTime(item.created_at);
          const row = `<tr class="border">
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${index + 1}</td>
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.guest_id}</td>
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.name}</td>
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.company_name}</td>
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.gender}</td>
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${formattedDateTime}</td>
          </tr>`;
          tableBody.insertAdjacentHTML("beforeend", row);
      });
  }

  getCheckInList();

</script>

<script>
  // Listen for keydown event
  document.addEventListener('keydown', function(e) {
      // add scan property to window if it does not exist
      if (!window.hasOwnProperty('scan')) {
          window.scan = [];
      }
      
      // If the key stroke appears after 10 ms, empty the scan array
      if (window.scan.length > 0 && (e.timeStamp - window.scan.slice(-1)[0].timeStamp) > 10) {
          window.scan = [];
      }
      
      // If the key pressed is Enter and scan array contains keystrokes
      // Dispatch a 'scanComplete' event with the scanned string as detail
      // Empty the scan array after dispatching the event
      if (e.key === "Enter" && window.scan.length > 0) {
          let scannedString = window.scan.reduce(function(scannedString, entry) {
              return scannedString + entry.key;
          }, "");
          window.scan = [];
          // console.log(scannedString); 
          return document.dispatchEvent(new CustomEvent('scanComplete', { detail: scannedString }));
      }
      
      // Do not listen to the Shift event, since the key for the next keystroke already contains a capital letter
      // or to be specific the letter that appears when that key is pressed with the Shift key
      if (e.key !== "Shift") {
          // Push `key`, `timeStamp`, and calculated `timeStampDiff` to the scan array
          let data = { key: e.key, timeStamp: e.timeStamp, timeStampDiff: window.scan.length > 0 ? e.timeStamp - window.scan.slice(-1)[0].timeStamp : 0 };
          window.scan.push(data);
      }
  });
  // Listen to the `scanComplete` event on the document

  document.addEventListener('scanComplete', async function(e) { 
      console.log(e.detail); // Log the scanned ID

      try {
          let res = await axios.get("/guest/"+ parseInt(e.detail));
          // console.log("API Response:", res);

          if (res.data.success) {
            // If guest information is retrieved successfully, save the ID in the check-ins table
            try {
                let checkInResponse = await axios.post("/check-in", { guest_invitation_id: res.data.data.id });
                // console.log("Check-in Response:", checkInResponse); 

                if (checkInResponse.data.status == 'success') {
                  getCheckInList();
                  successToast(checkInResponse.response.data['message']);
                }

            } catch (error) {
              // console.error("Error saving check-in:", error); 
              if (error.response) {
                if (error.response.status === 400 && error.response.data['status'] === 'error') {
                  errorToast(error.response.data['message']);
                  return;
                }
              }
            }
        }

      } catch (error) {
        if (error.response) {
          errorToast(error.response.data['message']);
        }
      }
  });

</script>
@endpush

@endsection
