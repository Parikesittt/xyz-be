@php
    $user = auth()->guard('api')->user();

    // Retrieve company or branch info
    if ($user && ($user->office_team == 3 || ($user->office_team == 4 && $user->is_branch == 0))) {
        $company = \App\Models\Companys::find($user->company_id);
    } else {
        $branch = \App\Models\Office_branchs::where('location_id', $user->location_id)
                                           ->where('company_id', $user->company_id)
                                           ->where('is_active', 1)
                                           ->first();
    }
@endphp

@if(isset($company))
    <table class="header" width="100%">
        <tbody>
            <tr>
                <th valign="left" width="112">
                    <img src="{{ url($company->file_path) }}" height="85" width="120" style="margin-bottom: 0;"/>
                </th>
                <th align="right">
                    <strong style="font-size:13pt; font-family:arial !important">{{ $company->name }}</strong>
                    <br><h style="font-size:9pt; font-family:arial !important">{{ $company->address }}</h>
                    <br><h style="font-size:9pt; font-family:arial !important">{{ $company->phone }}</h>
                    <br><h style="font-size:9pt; font-family:arial !important">{{ $company->email }}</h>
                </th>
            </tr>
        </tbody>
        {{-- <hr color="#000000" width="100%" size="3"> --}}
    </table>

@elseif(isset($branch))
    <table class="header" width="100%">
        <tbody>
            <tr>
                <th valign="left" width="112">
                    <img src="{{ url($branch->file_path) }}" height="85" width="120" />
                </th>
                <th align="right">
                    <strong style="font-size:13pt; font-family:arial !important">{{ $branch->name }}</strong>
                    <br><h style="font-size:9pt; font-family:arial !important">({{ $branch->description }})</h>
                    <br><h style="font-size:9pt; font-family:arial !important">{{ $branch->address }}</h>
                    <br><h style="font-size:9pt; font-family:arial !important">{{ $branch->phone }}</h>
                    <br><h style="font-size:9pt; font-family:arial !important">{{ $branch->email }}</h>
                </th>
            </tr>
        </tbody>
        <hr color="#000000" width="100%" size="3">
    </table>
@endif
