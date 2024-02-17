@extends('layout.app')
@section('content')
<div class="bg-[#fff] w-full lg:max-w-7xl mx-auto px-4 py-2">
  <div class="overflow-x-auto">
    <div class="inline-block min-w-full rounded-md overflow-hidden">
      <h2 class="text-2xl text-[#090B10] font-montserrat font-bold py-3">Check-In List</h2>
      <table class="min-w-full" id="checkInTable">
        <thead>
          <tr class="border">
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">ID</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Name</th>
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

<script>
  function formatDateTime(dateTimeString) {
      const date = new Date(dateTimeString);
      const utcOffsetMinutes = date.getTimezoneOffset(); // Get the timezone offset in minutes
      date.setMinutes(date.getMinutes() + utcOffsetMinutes); // Adjust date by subtracting the offset
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
          const response = await fetch("/check-in/list");
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
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.name}</td>
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${item.guest_invitation.gender}</td>
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">${formattedDateTime}</td>
          </tr>`;
          tableBody.insertAdjacentHTML("beforeend", row);
      });
  }

  getCheckInList();
  
</script>

@endsection
