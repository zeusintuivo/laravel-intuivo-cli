			select	
			cal.date_value,
			to_char(cal.date_value,'DAY'),
			cyc.letter,
			cal.a,
			cal.b,
			cal.c,
			cal.d,
			cal.e,
			cal.f,
			cal.insession,
			cal.schoolid,
			t.yearid + 1990
			from calendar_day cal
			inner join cycle_day cyc on cyc.id = cal.cycle_day_id
			inner join schools sch on sch.school_number = cal.schoolid
			inner join terms t on t.schoolid = cal.schoolid and t.isyearrec=1 and t.firstday <= cal.date_value and cal.date_value <= t.lastday
			where cal.schoolid = :schoolid and t.yearid + 1990 = :schoolyear
			order by cal.date_value   