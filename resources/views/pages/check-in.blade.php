@extends('layout.app')
@section('content')

<div class="bg-[#fff] w-full lg:max-w-7xl mx-auto px-4 py-2">
  <div class="overflow-x-auto">
    <div class="inline-block min-w-full rounded-md overflow-hidden">
      <h2 class="text-2xl text-[#090B10] font-montserrat font-bold py-3">Check-In List</h2>
      <table class="min-w-full" id="checkInTable">
        <thead>
          <tr class="border">
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">SI</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Guest ID</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Name</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Company Name</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Gender</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Entry Time</th>
          </tr>
        </thead>
        <tbody id="checkInTableBody">
          
        </tbody>
      </table>
    </div>
  </div>
</div>

@push('script')
<script>
  let serialId = document.querySelectorAll('#checkInTable tbody tr').length;

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
      const response = await fetch(baseUrl + "/api/check-in/list");
      if (!response.ok) {
        throw new Error("Failed to fetch check-in list.");
      }
      const data = await response.json();

      const tableBody = document.getElementById("checkInTableBody");
      tableBody.innerHTML = ""; // Clear existing rows
      // Reset serialId based on the existing number of rows
      serialId = document.querySelectorAll('#checkInTable tbody tr').length;

      data.data.forEach((item, index) => {
        const formattedDateTime = formatDateTime(item.created_at);
        const row = `<tr class="border">
          <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${++serialId}</td>
          <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.guest_id}</td>
          <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.name}</td>
          <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.company_name}</td>
          <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.gender}</td>
          <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${formattedDateTime}</td>
        </tr>`;
        tableBody.insertAdjacentHTML("beforeend", row);
      });

      if (!$.fn.DataTable.isDataTable('#checkInTable')) {
            $('#checkInTable').DataTable({
                order: [[0, 'desc']],
            });
        } else {
            $('#checkInTable').DataTable().draw();
        }
    } catch (error) {
      console.error(error);
    }
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
          var baseUrl = '{{ config('app.url') }}';
          let res = await axios.get(baseUrl + "/api/guest/"+ parseInt(e.detail));
          // console.log("API Response:", res);

          if (res.data.success) {
            // If guest information is retrieved successfully, save the ID in the check-ins table

            try {
                let checkInResponse = await axios.post(baseUrl + "/api/check-in", { guest_invitation_id: res.data.data.id });
                // console.log("Check-in Response:", checkInResponse); 

                if (checkInResponse.data.status == 'success') {
                  const successMessage = checkInResponse.data.message;
                  successToast(successMessage);

                  const formattedDateTime = formatDateTime(new Date().toISOString());

                  $('#checkInTable').DataTable().row.add([
                    ++serialId,
                    res.data.data.guest_id,
                    res.data.data.name,
                    res.data.data.company_name,
                    res.data.data.gender,
                    formattedDateTime
                  ]).draw(false);

                  // getCheckInList(); 
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
