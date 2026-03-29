@extends("pdfs.layout")

@section("styles")
  <style>
    .doc {
  font-size: 14px;
  color: #111;
  margin-left: 0.3in;
  font-family: "Times New Roman", Times, serif;
}
    .info-row {
      margin-top: 24px;
    }

    .info-left {
      width: 60%;
      float: left;
    }

    .info-right {
      width: 38%;
      float: right;
      text-align: right;
    }

    .line {
      margin: 4px 0;
    }

    .section-title {
     text-align: center;
      font-weight: 700;
      letter-spacing: 2px;
      font-size: 16px;
      margin: 2px 0 18px;
    }

    .cert-body {
      margin-top: 40px;
      text-align: justify;
      line-height: 1.8;
      font-size: 14px;
      
    }

    .remarks {
      margin-top: 80px;
      font-size: 13px;
    }

    .footer-note {
      margin-top: 70px;
      text-align: center;
      font-size: 14px;
    }
  </style>
@endsection

@section("content")
  @php
    $p = $consultation->patient;
    $appt = $consultation->appointment;

    $fullName = trim(
        collect([$p->first_name ?? null, $p->middle_name ?? null, $p->last_name ?? null])
            ->filter()
            ->implode(" "),
    );

    // if you don't have stored age, compute it (optional)
    $age = $p->age ?? null;
    if (!$age && !empty($p->date_of_birth)) {
        $age = \Carbon\Carbon::parse($p->date_of_birth)->age;
    }

    $sex = !empty($p->gender) ? strtoupper($p->gender) : null;
    $address = $p->address ?? null;

    $dateValue = $appt?->appointment_date ?: $consultation->created_at;
    $dateLabel = \Carbon\Carbon::parse($dateValue)->format("M d, Y");

    $diagnosis = trim((string) ($consultation->diagnosis ?? ""));
    $remarks = trim((string) ($consultation->remarks ?? ""));

    $referralTo = trim((string) ($consultation->referral_to ?? ""));
    $referralReason = trim((string) ($consultation->referral_reason ?? ""));
  @endphp

  <div class="doc">
  <div class="title">MEDICAL CERTIFICATE</div>

  <div class="row clearfix info-row">
    
  

    <div class="info-right">
      <div style="font-size:14px;">
        <span class="muted">Date :</span> {{ $dateLabel }}
      </div>
    </div>
  </div>

  <div class="cert-body">
     <span style="display:block; text-indent:0.5in;">
    This is to certify that
  
    <strong>{{ $fullName !== "" ? strtoupper($fullName) : "________________" }}</strong>,
    {{ $age ? $age . "y.o" : "___y.o" }}, {{ $sex ?: "____" }},
    currently residing at <span>{{ $address ?: "________________" }}</span>
    has been examined in my clinic on <span>{{ $dateLabel }}</span>
    and was given the following diagnosis:
   <strong><span>{{ $diagnosis !== "" ? strtoupper($diagnosis) : "________________________" }}</span></strong> </span>
  </div>

  <div class="remarks">
    <strong>REMARKS:</strong> {{ $remarks !== "" ? $remarks : "—" }}
  </div>

  <div class="footer-note">
    This certificate is issued upon the request of {{ $referralTo ?: "________________" }} for
    {{ $referralReason ?: "________________" }} purposes except medico legal.
  </div>

  @include("pdfs.partials.doctor-signature", ["consultation" => $consultation])
  </div>
@endsection
