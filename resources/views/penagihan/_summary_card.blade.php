<div class="row row-cols-1 row-cols-md-6 g-3 mb-4">

    {{-- Kunjungan --}}
    @include('penagihan._summary_card', [
        'label' => 'Kunjungan',
        'value' => $totalVisit,
        'class' => 'text-primary',
    ])

    {{-- Ditagih --}}
    @include('penagihan._summary_card', [
        'label' => 'Ditagih',
        'value' => 'Rp ' . number_format($totalTagih, 0, ',', '.'),
        'class' => 'text-dark',
    ])

    {{-- Dibayar --}}
    @include('penagihan._summary_card', [
        'label' => 'Dibayar',
        'value' => 'Rp ' . number_format($totalBayar, 0, ',', '.'),
        'class' => 'text-success',
    ])

    {{-- % Keberhasilan --}}
    @include('penagihan._summary_card', [
        'label' => '% Keberhasilan',
        'value' => $successRate . '%',
        'class' => $successRate >= 60 ? 'text-success' : ($successRate >= 30 ? 'text-warning' : 'text-danger'),
    ])

    {{-- Janji Bayar --}}
    @include('penagihan._summary_card', [
        'label' => 'Janji Bayar',
        'value' => $janjiCount,
        'class' => 'text-dark',
    ])

    {{-- ✅ Follow-Up Pending --}}
    @include('penagihan._summary_card', [
        'label' => 'Follow-Up Pending',
        'value' => $followUpPending,
        'class' => 'text-danger',
    ])

    {{-- ✅ Follow-Up Berhasil --}}
    @include('penagihan._summary_card', [
        'label' => 'Follow-Up Berhasil',
        'value' => $followUpBerhasil,
        'class' => 'text-success',
    ])

    {{-- ✅ Rate keberhasilan Follow-Up --}}
    @include('penagihan._summary_card', [
        'label' => '% Sukses Follow-Up',
        'value' => $successRateFollowUp . '%',
        'class' =>
            $successRateFollowUp >= 60
                ? 'text-success'
                : ($successRateFollowUp >= 30
                    ? 'text-warning'
                    : 'text-danger'),
    ])

</div>
