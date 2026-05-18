// Калькулятор для инженерных систем

const Calculator = {
    // Водоснабжение и канализация (ВК)
    calculateWater: function(buildingType, peopleCount, floors, volume) {
        const norms = ENGINEERING_NORMS.modules.water.parameters;
        let qNorm = norms.q_norm_living.value; // default for residential
        if (buildingType === 'office' || buildingType === 'mall') {
            qNorm = 15; // л/сут для офисов и ТЦ
        }
        // Хоз-бытовой расход
        const Qday = (peopleCount * qNorm) / 1000; // м³/сут

        // ВПВ - определяем количество струй
        let jetsCount = norms.fire_jets_count.value; // default from norms
        const jetFlow = norms.fire_jet_flow.value; // л/с

        if (buildingType === 'residential') {
            if (floors <= 12) jetsCount = 1;
            else if (floors <= 16) jetsCount = 2;
            else jetsCount = 3;
        } else {
            // Общественное здание
            if (volume < 5000) jetsCount = 1;
            else jetsCount = 2;
        }

        const Qfire = jetsCount * jetFlow * 3.6; // м³/ч
        const Vtank = (Qfire * 3) + (Qday * 0.1); // м³

        return {
            Qday: Qday.toFixed(3),
            Qfire: Qfire.toFixed(2),
            jetsCount: jetsCount,
            Vtank: Vtank.toFixed(2),
            qNorm: qNorm,
            formula: `Q_сут = (${peopleCount} * ${qNorm}) / 1000 = ${Qday.toFixed(3)} м³/сут; Q_впв = ${jetsCount} * ${jetFlow} * 3.6 = ${Qfire.toFixed(2)} м³/ч; V_резервуара = (${Qfire.toFixed(2)} * 3) + (${Qday.toFixed(3)} * 0.1) = ${Vtank.toFixed(2)} м³`
        };
    },

    // Отопление и вентиляция (ОВ)
    calculateHeat: function(buildingType, volume, tOut = -25) {
        const norms = ENGINEERING_NORMS.modules.heat.parameters;
        let q0 = norms.q0_ot.value; // default 0.45
        if (buildingType === 'residential') {
            q0 = 0.45; // среднее между 0.4-0.5
        } else if (buildingType === 'office') {
            q0 = 0.45;
        } else {
            q0 = 0.45; // для ТЦ и других
        }

        const tIn = norms.t_in.value; // 20°C
        // Если tOut передан, используем его, иначе из норм
        const tOutActual = tOut !== undefined ? tOut : norms.t_out.value;
        const Qheat = (q0 * volume * (tIn - tOutActual)) / 1000; // кВт

        // ГВС: +30%
        const kGvsCirc = norms.k_gvs_circ.value; // 1.3 (30% примерно)
        const Qgvs = Qheat * kGvsCirc; // с учетом циркуляции

        // Перевод в Гкал/ч
        const QheatGcal = Qheat * 0.00086;
        const QgvsGcal = Qgvs * 0.00086;

        return {
            Qheat: Qheat.toFixed(2),
            Qgvs: Qgvs.toFixed(2),
            QheatGcal: QheatGcal.toFixed(4),
            QgvsGcal: QgvsGcal.toFixed(4),
            q0: q0,
            tOut: tOutActual,
            formula: `Q_от = ${q0} * ${volume} * (${tIn} - (${tOutActual})) / 1000 = ${Qheat.toFixed(2)} кВт; Q_гвс = ${Qheat.toFixed(2)} * ${kGvsCirc} = ${Qgvs.toFixed(2)} кВт`
        };
    },

    // Электроснабжение (ЭОМ)
    calculateElectricity: function(buildingType, area, peopleCount) {
        const norms = ENGINEERING_NORMS.modules.electricity.parameters;
        let Pcalc = 0;
        let specificLoad = 0;

        if (buildingType === 'residential') {
            // Для жилья: таблицы удельных нагрузок на квартиру (от 10 до 20 кВт)
            // Упрощенно: берем 15 кВт на квартиру, коэффициент одновременности 0.7
            const apartmentsCount = peopleCount / 3; // примерно 3 человека на квартиру
            const loadPerApartment = 15; // кВт
            const kSimult = 0.7;
            Pcalc = apartmentsCount * loadPerApartment * kSimult;
            specificLoad = Pcalc / area; // Вт/м²
        } else {
            // Для офисов и ТЦ: 40–60 Вт/м²
            specificLoad = 50; // среднее
            Pcalc = (specificLoad * area) / 1000; // кВт
        }

        const cosPhi = norms.cos_phi.value;
        const Sfull = Pcalc / cosPhi; // кВА

        return {
            Pcalc: Pcalc.toFixed(2),
            Sfull: Sfull.toFixed(2),
            specificLoad: specificLoad.toFixed(2),
            cosPhi: cosPhi,
            formula: `P_расч = ${specificLoad} * ${area} / 1000 = ${Pcalc.toFixed(2)} кВт; S_полн = ${Pcalc.toFixed(2)} / ${cosPhi} = ${Sfull.toFixed(2)} кВА`
        };
    }
};