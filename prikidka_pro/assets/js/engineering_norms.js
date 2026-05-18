// Данные нормативов из engineering_norms.json в формате JS
const ENGINEERING_NORMS = {
    "modules": {
        "heat": {
            "title": "Отопление и вентиляция (ОВ)",
            "base_norm": "МДС 41-4.2000, СП 60.13330.2020",
            "parameters": {
                "q0_ot": {
                    "label": "Удельная тепловая характеристика здания (q0)",
                    "value": 0.45,
                    "unit": "Вт/(м³·°C)",
                    "editable": true,
                    "source": "МДС 41-4.2000, Приложение 1"
                },
                "t_in": {
                    "label": "Расчетная температура внутри (t_вн)",
                    "value": 20,
                    "unit": "°C",
                    "editable": true,
                    "source": "ГОСТ 30494-2011"
                },
                "t_out": {
                    "label": "Температура холодной пятидневки (t_н)",
                    "value": -25,
                    "unit": "°C",
                    "editable": true,
                    "source": "СП 131.13330.2020 (Климатология)"
                },
                "k_gvs_circ": {
                    "label": "Коэффициент потерь на циркуляцию ГВС",
                    "value": 1.3,
                    "unit": "",
                    "editable": true,
                    "source": "Руководство АВОК (Водяное отопление)"
                },
                "k_recup": {
                    "label": "Доля тепла после рекуперации",
                    "value": 0.4,
                    "unit": "",
                    "editable": true,
                    "source": "Руководство АВОК (Вентоборудование)"
                }
            },
            "formulas_display": [
                "Q_от = q0 * V * (t_вн - t_н) / 1000 [кВт]",
                "Q_гвс_итог = Q_гвс_база * k_gvs_circ"
            ]
        },
        "water": {
            "title": "Водоснабжение и водоотведение (ВК)",
            "base_norm": "СП 30.13330.2020, СП 10.13130.2020",
            "parameters": {
                "q_norm_living": {
                    "label": "Норма расхода на жителя (q_day)",
                    "value": 250,
                    "unit": "л/сут",
                    "editable": true,
                    "source": "СП 30.13330.2020, Приложение А"
                },
                "k_hr_max": {
                    "label": "Коэфф. часовой неравномерности (K_hr)",
                    "value": 1.56,
                    "unit": "",
                    "editable": true,
                    "source": "СП 30.13330.2020"
                },
                "fire_jets_count": {
                    "label": "Количество пожарных струй ВПВ",
                    "value": 2,
                    "unit": "шт",
                    "editable": true,
                    "source": "СП 10.13130.2020, Таблица 1"
                },
                "fire_jet_flow": {
                    "label": "Расход одной струи ВПВ",
                    "value": 2.5,
                    "unit": "л/с",
                    "editable": true,
                    "source": "СП 10.13130.2020, Таблица 1"
                }
            },
            "formulas_display": [
                "Q_сут = (N_людей * q_day) / 1000 [м³/сут]",
                "Q_впв = (Струи * Расход_л_с * 3.6) [м³/ч]",
                "V_резервуара = (Q_впв * 3 часа) + (Q_сут * 0.1) [м³]"
            ]
        },
        "electricity": {
            "title": "Электроснабжение (ЭОМ)",
            "base_norm": "СП 256.1325800.2016",
            "parameters": {
                "cos_phi": {
                    "label": "Коэффициент мощности (cos φ)",
                    "value": 0.85,
                    "unit": "",
                    "editable": true,
                    "source": "СП 256.1325800.2016"
                },
                "k_demand_vent": {
                    "label": "Коэфф. спроса для вентиляции (Kc)",
                    "value": 0.7,
                    "unit": "",
                    "editable": true,
                    "source": "СП 256.1325800.2016, Таблица 7.1"
                },
                "k_vav_savings": {
                    "label": "Снижение мощности при VAV системах",
                    "value": 0.7,
                    "unit": "",
                    "editable": true,
                    "source": "Руководство SE по автоматизации"
                }
            },
            "formulas_display": [
                "P_расч = P_устан * Kc [кВт]",
                "S_полн = P_расч / cos_phi [кВА]"
            ]
        }
    }
};