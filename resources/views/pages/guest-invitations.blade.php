@extends('layout.app')
@section('content')
<style>
  .even td, .odd td {
    border-bottom: 1px solid black;
}
</style>
<div class="bg-[#fff] w-full lg:max-w-7xl mx-auto px-4 py-2">
  <div class="overflow-x-auto">
    <div class="inline-block min-w-full rounded-md overflow-hidden">
      <div class="flex justify-between items-center">
        <h2 class="text-2xl text-[#090B10] font-montserrat font-bold py-3">Guest Invitations</h2>
        <button id="addGuestModalBtn" class="border-2 border-[#099000] text-[#099000] hover:bg-[#099000] hover:text-white text-sm font-nunito font-bold py-1 px-4 rounded whitespace-nowrap">Add Guest</button>
      </div>
      <table class="min-w-full" id="myTable">
        <thead>
          <tr class="border">
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">ID</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Name</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Gender</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Barcode</th>
            <th></th>
          </tr>
        </thead>
        <tbody >
          @if(count($guestInvitations) > 0)
            @php($i = 1)
            @foreach ($guestInvitations as $guest)
            <tr class="border">
              <td class=" text-[#090B10] text-sm font-nunito font-semibold p-4">{{ $i++ }}</td>
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">{{ $guest->name }}</td>
              <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">{{ $guest->gender }}</td>
              <td id="barcode-svg-{{ $guest->unique_identifier }}">
                  {!!  DNS2D::getBarcodeSVG($guest->unique_identifier, 'DATAMATRIX' ,10,10) !!}
                  <br>
                  <button 
                    onclick="downloadBarcode('barcode-svg-{{ $guest->unique_identifier }}', '{{ $guest->unique_identifier }}', 'jpg')" 
                    class="border-2 border-[#099000] text-[#099000] hover:bg-[#099000] hover:text-white text-sm font-nunito font-bold py-1 px-4 rounded"
                    >Download Barcode
                  </button>
              </td>
              <td class="text-end px-4">
                <a href="{{ url('guest/delete/'. $guest->id) }}" class="border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white text-sm font-nunito font-bold py-1 px-4 rounded"  onclick="return confirm('Are you sure you want to delete this Guest?')">Delete</button>
              </td>
            </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal markup -->
<div id="addGuestModal" class="modal fixed w-full h-full top-0 left-0 flex items-center justify-center hidden">
  <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
    <!-- Add your modal content here -->
    <div class="modal-content py-4 text-left px-6">
      <div class="flex justify-end items-center">
        <button id="closeAddGuestModalBtn" class="text-3xl leading-none">&times;</button>
      </div>
      <div class="">
        <div class="pb-4">
          <label class="block text-sm text-[#090B10] font-montserrat font-semibold pb-2">Name</label>
          <input
            type="text"
            id="name"
            class="block w-full rounded-md border border-[#D9DDE3] text-sm text-[#090B10] font-nunito font-normal px-3 py-2"
            placeholder="Enter name"
          />
        </div>
        <div class="pb-4">
          <label class="block text-sm text-[#090B10] font-montserrat font-semibold pb-2">Gender</label>
          <select
            id="gender"
            class="block w-full rounded-md border border-[#D9DDE3] text-sm text-[#090B10] font-nunito font-normal px-3 py-2"
            placeholder="Select gender"
          >
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <button onclick="addGuest()" class="block w-full border-2 border-[#099000] text-[#099000] font-nunito font-bold hover:bg-[#099000] hover:text-white px-6 py-2 rounded-md">Submit</button>
      </div>
    </div>
  </div>
</div>

@push('script')
<script>  
  async function addGuest() {
    try {    
        let name = document.getElementById('name').value;
        let gender = document.getElementById('gender').value;
        document.getElementById('closeAddGuestModalBtn').click();

        showLoader();
         var baseUrl = '{{ config('app.url') }}';
        let res=await axios.post(baseUrl+"/add-guest", { 
          name: name,
          gender: gender,
        });
        hideLoader();
        
        if(res.status === 201 && res.data['status']==='success'){
            successToast(res.data['message']);
            window.location.href = "{{ url('/guest-invitations') }}";
        }
    }catch(error) {
        hideLoader(); 

        if (error.response) {
            if (error.response.status === 422 && error.response.data['status'] === 'error') {
                displayValidationErrors(error.response.data['errors']);
                return;
            }
            errorToast(error.response.data['message']);
        }
    }
    
  }

  function displayValidationErrors(errors) {
    for (let field in errors) {
        errorToast(errors[field][0]); // Display the first error for each field
    }
  }

  // Barcode Script
  var counter = 0;

  function downloadBarcode(elementId, filename, format) {
      var svgElement = document.getElementById(elementId).querySelector('svg');
      var svgData = new XMLSerializer().serializeToString(svgElement);

      // Create an Image object
      var image = new Image();

      // Load SVG data into the Image object
      image.src = "data:image/svg+xml;charset=utf-8," + encodeURIComponent(svgData);
      image.onload = function() {
          // Create a canvas
          var canvas = document.createElement("canvas");
          var context = canvas.getContext("2d");

          // Set canvas dimensions
          canvas.width = image.width + 100; // Add 100px to width for larger white background
          canvas.height = image.height + 100; // Add 100px to height for larger white background

          // Draw a larger white background
          context.fillStyle = "#FFFFFF";
          context.fillRect(0, 0, canvas.width, canvas.height);

          // Draw the SVG image onto the canvas
          context.drawImage(image, 50, 50); // Offset by 50px to center the SVG image

          // Get the image data URL from the canvas
          var imageData = canvas.toDataURL("image/jpeg");

          // Create a link element for downloading
          var a = document.createElement("a");
          a.download = filename + "_" + counter + "." + format; // Unique filename
          a.href = imageData;

          // Trigger click event to initiate download
          a.click();

          // Increment counter for next download
          counter++;
      };
  }
</script>

@if(session('success'))
<script>
  // Display success toast
  window.onload = ()=>{
    successToast("{{ session('success') }}");
  }
</script>
@endif
@endpush

@endsection
