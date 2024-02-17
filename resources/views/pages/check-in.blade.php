@extends('layout.app')
@section('content')
<div class="bg-[#fff] w-full lg:max-w-7xl mx-auto px-4 py-2">
  <div class="overflow-x-auto">
    <div class="inline-block min-w-full rounded-md overflow-hidden">
      <h2 class="text-2xl text-[#090B10] font-montserrat font-bold py-3">Check-In List</h2>
      <table class="min-w-full">
        <thead>
          <tr class="border">
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">ID</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Name</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Gender</th>
            <th class="text-left text-base text-gray-600 font-nunito font-bold px-4 py-3">Entry Time</th>
          </tr>
        </thead>
        <tbody >
          <tr class="border">
            <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">01</td>
            <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">Motaleb</td>
            <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">Male</td>
            <td class="text-[#090B10] text-sm font-nunito font-semibold p-4">20-12-024 - 4:00 AM</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection