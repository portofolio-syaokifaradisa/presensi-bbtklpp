<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'pagi',
        'sore',
        'tanggal',
        'status',
        'keterangan'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $appends = [
        'tanggal_indo',
        'hari',
        'late',
        'jam_kerja',
        'minute_late',
        'minute_jam_kerja',
    ];

    public function getTanggalIndoAttribute(){
        return Carbon::parse($this->tanggal)->translatedFormat('d F Y');
    }

    public function getHariAttribute(){
        return Carbon::parse($this->tanggal)->locale('id')->dayName;
    }

    public function getMinuteLateAttribute(){
        if(!$this->pagi){
            return 0;
        }

        $time = Carbon::parse('06:30:00');
        $pagiTime = Carbon::parse($this->pagi);

        if($pagiTime <= $time){
            return 0;
        }

        $timeDifference = $time->diff($pagiTime);

        $lateHours = $timeDifference->h;
        $lateMinutes = $timeDifference->i;

        $late = ($lateHours * 60) + $lateMinutes;
        return $late;
    }

    public function getLateAttribute(){
        if(!$this->pagi){
            return '-';
        }

        $time = Carbon::parse('06:30:00');
        $pagiTime = Carbon::parse($this->pagi);

        if($pagiTime <= $time){
            return '-';
        }

        $timeDifference = $time->diff($pagiTime);

        $lateHours = $timeDifference->h;
        $lateMinutes = $timeDifference->i;

        $late = '';
        if ($lateHours > 0) {
            $late = $lateHours . " Jam";
            if ($lateMinutes > 0) {
                $late = $late . " " .$lateMinutes . " Menit";
            }
        }else if ($lateMinutes > 0) {
            $late = $lateMinutes . " Menit";
        }else{
            $late = '-';
        }

        return $late;
    }

    public function getJamKerjaAttribute(){
        if($this->status == "Dinas Luar"){
            return '7 Jam 30 Menit';
        }

        if(!$this->pagi){
            return '-';
        }

        $morningTime = Carbon::parse($this->pagi);
        $soreTime = Carbon::parse($this->sore);

        $startRest = Carbon::parse("11:00");
        $endRest = Carbon::parse("12:00");

        $timeDifference = $morningTime->diff($soreTime);

        $workHour = $timeDifference->h;
        $workMinute = $timeDifference->i;

        if($morningTime <= $startRest && $soreTime >= $endRest){
            if($workHour > 0){
                $workHour = $workHour - 1;
            }else{
                $workMinute = 0;
            }
        }

        if($morningTime >= $startRest && $morningTime <= $endRest){
            $timeDifference = $endRest->diff($soreTime);
            $workHour = $timeDifference->h;
            $workMinute = $timeDifference->i;
        }

        $late = '';
        if ($workHour > 0) {
            $late = $workHour . " Jam";
            if ($workMinute > 0) {
                $late = $late . " " .$workMinute . " Menit";
            }
        }else if ($workMinute > 0) {
            $late = $workMinute . " Menit";
        }else{
            $late = '-';
        }

        return $late;
    }

    public function getMinuteJamKerjaAttribute(){
        if($this->status == "Dinas Luar"){
            return (60 * 7) + 30;
        }

        if(!$this->pagi){
            return 0;
        }

        $morningTime = Carbon::parse($this->pagi);
        $soreTime = Carbon::parse($this->sore);

        $startRest = Carbon::parse("11:00");
        $endRest = Carbon::parse("12:00");

        $timeDifference = $morningTime->diff($soreTime);

        $hours = $timeDifference->h;
        $minutes = $timeDifference->i;

        if($morningTime <= $startRest && $soreTime > $endRest){
            if($hours > 0){
                $hours = $hours - 1;
            }else{
                $minutes = 0;
            }
        }

        $minuteWork = ($hours * 60 ) + $minutes;
        if($morningTime > $startRest && $morningTime < $endRest && $soreTime > $endRest){
            $restDifference = $morningTime->diff($endRest);
            $minuteWork = $minuteWork - $restDifference->i;
        }

        return $minuteWork;
    }
}
