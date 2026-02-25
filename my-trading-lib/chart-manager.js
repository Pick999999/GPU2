/**
 * ChartManager Class
 * -----------------------------------------------------------------------
 * 📖 คู่มือการใช้งาน (THAI MANUAL)
 * -----------------------------------------------------------------------
 * 
 * 🛠️ สิ่งที่ต้องเตรียมในหน้า Webpage (HTML Requirements):
 * ต้องมี <div> containers จำนวน 3 ตัว โดยกำหนด id ให้ตรงตามนี้:
 * 1. <div id="mainChart"></div>   --> สำหรับแสดงกราฟแท่งเทียนหลักและเส้น MA
 * 2. <div id="rsiChart"></div>    --> สำหรับแสดงกราฟ RSI (แยกต่างหาก)
 * 3. <div id="choppyChart"></div> --> สำหรับแสดงกราฟ Choppiness Index (แยกต่างหาก)
 * 
 * ⚠️ หมายเหตุ: ขนาด (Height) ถูกกำหนดไว้ใน code นี้แล้ว (500px, 400px) 
 * หากต้องการปรับขนาด ให้แก้ไขที่ property 'height' ใน method initialize()
 * 
 * ⚙️ วิธีการเรียกใช้งาน (How to use):
 * 1. สร้าง Instance: 
 *    const charts = new ChartManager();
 *    (เมื่อเรียกใช้ กราฟเปล่าจะถูกสร้างขึ้นใน div ที่เตรียมไว้ทันที)
 * 
 * 2. การอัปเดตข้อมูล (Update Data):
 *    - charts.updateCandles(data)  -> อัปเดตกราฟแท่งเทียนทั้งหมด
 *    - charts.updateMA(index, data) -> อัปเดตเส้น MA (index: 0, 1, หรือ 2)
 *    - charts.updateRSI(data)       -> อัปเดตกราฟ RSI
 *    - charts.updateChoppiness(data) -> อัปเดตกราฟ Choppy
 * 
 * 3. การอัปเดต Real-time (Update Live):
 *    - charts.updateLiveCandle(point)
 *    - charts.updateLiveMA(index, point)
 *    - ... และอื่นๆ
 * -----------------------------------------------------------------------
 */
class ChartManager {
    constructor() {
        this.mainChart = null;
        this.rsiChart = null;
        this.choppyChart = null;
        this.candleSeries = null;
        this.maSeries = [];
        this.rsiSeries = null;
        this.choppySeries = null;
        this.initialize();
    }

    initialize() {
        // Main Chart
        this.mainChart = LightweightCharts.createChart(document.getElementById('mainChart'), {
            layout: { background: { color: '#0f1729' }, textColor: '#d1d4dc' },
            grid: { vertLines: { color: '#1a2340' }, horzLines: { color: '#1a2340' } },
            timeScale: { timeVisible: true, secondsVisible: false },
            height: 500
        });

        this.candleSeries = this.mainChart.addCandlestickSeries({
            upColor: '#00ff88', downColor: '#ff4444',
            borderUpColor: '#00ff88', borderDownColor: '#ff4444',
            wickUpColor: '#00ff88', wickDownColor: '#ff4444'
        });

        // MA Lines
        this.maSeries.push(this.mainChart.addLineSeries({ color: '#4a9eff', lineWidth: 2, title: 'MA1' }));
        this.maSeries.push(this.mainChart.addLineSeries({ color: '#ff9500', lineWidth: 2, title: 'MA2' }));
        this.maSeries.push(this.mainChart.addLineSeries({ color: '#00ff88', lineWidth: 2, title: 'MA3' }));

        // RSI Chart
        this.rsiChart = LightweightCharts.createChart(document.getElementById('rsiChart'), {
            layout: { background: { color: '#0f1729' }, textColor: '#d1d4dc' },
            grid: { vertLines: { color: '#1a2340' }, horzLines: { color: '#1a2340' } },
            height: 400
        });
        this.rsiSeries = this.rsiChart.addLineSeries({ color: '#ff9500', lineWidth: 2 });

        // Add RSI levels
        this.rsiChart.addLineSeries({ color: '#666', lineWidth: 1, lineStyle: 2 })
            .setData([{ time: 0, value: 70 }, { time: 9999999999, value: 70 }]);
        this.rsiChart.addLineSeries({ color: '#666', lineWidth: 1, lineStyle: 2 })
            .setData([{ time: 0, value: 30 }, { time: 9999999999, value: 30 }]);

        // Choppiness Chart
        this.choppyChart = LightweightCharts.createChart(document.getElementById('choppyChart'), {
            layout: { background: { color: '#0f1729' }, textColor: '#d1d4dc' },
            grid: { vertLines: { color: '#1a2340' }, horzLines: { color: '#1a2340' } },
            height: 400
        });
        this.choppySeries = this.choppyChart.addLineSeries({ color: '#4a9eff', lineWidth: 2 });

        // Add Choppy levels
        this.choppyChart.addLineSeries({ color: '#00ff88', lineWidth: 1, lineStyle: 2 })
            .setData([{ time: 0, value: 38.2 }, { time: 9999999999, value: 38.2 }]);
        this.choppyChart.addLineSeries({ color: '#ff4444', lineWidth: 1, lineStyle: 2 })
            .setData([{ time: 0, value: 61.8 }, { time: 9999999999, value: 61.8 }]);
    }

    updateCandles(candles) {
        this.candleSeries.setData(candles);
        this.mainChart.timeScale().fitContent();
    }

    updateMA(index, data, visible = true) {
        if (this.maSeries[index]) {
            if (visible) {
                this.maSeries[index].setData(data);
            } else {
                this.maSeries[index].setData([]);
            }
        }
    }

    updateRSI(data) {
        this.rsiSeries.setData(data);
        this.rsiChart.timeScale().fitContent();
    }

    updateChoppiness(data) {
        this.choppySeries.setData(data);
        this.choppyChart.timeScale().fitContent();
    }

    updateLiveCandle(candle) {
        this.candleSeries.update(candle);
    }

    updateLiveMA(index, point) {
        if (this.maSeries[index]) {
            this.maSeries[index].update(point);
        }
    }

    updateLiveRSI(point) {
        this.rsiSeries.update(point);
    }

    updateLiveChoppiness(point) {
        this.choppySeries.update(point);
    }
}