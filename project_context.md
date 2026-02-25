# GPU2 - Deriv Trading Chart with GPU-Accelerated Indicators

## 📋 Project Overview

แอปพลิเคชัน Trading Chart ที่ใช้ GPU acceleration ในการคำนวณ Technical Indicators แบบ Real-time
เชื่อมต่อกับ Deriv API เพื่อดึงข้อมูลราคา Synthetic Indices
รองรับทั้ง GPU.js (WebGL) และ Native WebGPU (WGSL) พร้อม Smart CPU/GPU auto-selection

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        index.html                           │
│                    (UI & Styling)                           │
├─────────────────────────────────────────────────────────────┤
│                         app.js                              │
│                  (Main Application)                         │
│         - Orchestrates all components                       │
│         - Handles user interactions                         │
│         - Manages data flow                                 │
├──────────────────┬──────────────────┬───────────────────────┤
│   deriv-api.js   │ webgpu-indicators│   chart-manager.js    │
│                  │       .js        │                       │
│  - WebSocket     │  - GPU.js        │  - Lightweight Charts │
│  - Deriv API     │  - SMA/EMA/HMA   │  - Candlestick        │
│  - Historical    │  - RSI           │  - RSI Chart          │
│  - Live Stream   │  - Choppiness    │  - Choppiness Chart   │
│                  │  - Super Kernel  │                       │
└──────────────────┴──────────────────┴───────────────────────┘
          ▲                 ▲
          │                 │ 
┌─────────┴─────────────────┴─────────┐
│        multi-asset-loader.js        │
│      (Parallel Data & Calc)         │
│  - Batch Data Loading               │
│  - Super Kernel Execution           │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│     clsWGSLCompute.js (NEW)        │
│   Native WebGPU (WGSL) Engine      │
│  - Smart CPU/GPU Auto-Selection    │
│  - WGSL Compute Shaders           │
│  - Batch All Assets (1 submit)     │
│  - EMA/RSI/CI Indicators           │
│  - Singleton Cache Pattern         │
└─────────────────────────────────────┘
```

## 📁 File Structure

```
gpu2/
├── index.html              # Main HTML file with UI & styles
├── multiAsset.html         # Multi-Asset Loader Demo
├── test_multi_asset_bundle.html # Comprehensive Test Page for Bundle
├── testMultiAssetBundleV2.html  # Test Page with Asset Selection & GPU/WGSL Analysis
├── wgpl.html               # WebGPU (WGSL) Reference Example & Benchmark
├── MultiAssetBundle.js     # Consolidated Logic + Manager Class
├── testDerivBot.html       # Deriv Accumulator Bot (Main UI & Logic)
├── testDerivBotTrade.html  # Deriv Accumulator Bot (Trade Logic & Execution)
├── app.js                  # Main application controller
├── deriv-api.js            # Deriv WebSocket API wrapper
├── webgpu-indicators.js    # GPU.js indicator calculations (Hybrid GPU/CPU)
├── multi-asset-loader.js   # Parallel data loading & Super Kernel logic
├── chart-manager.js        # Chart rendering & management
├── js/
│   ├── clsWGSLCompute.js   # ★ Native WebGPU (WGSL) Compute Engine (standalone)
│   ├── clsAnalysisGeneratorV2.js # Analysis data generator
│   ├── clsAnalysisGenerator.js   # Analysis data generator V1
│   ├── indicators.js       # CPU indicator calculations
│   ├── SMCIndicator.js     # Smart Money Concepts indicator
│   ├── mainV4.js           # Main V4 application logic
│   └── ...                 # Other JS modules
├── README.md               # Basic readme
└── project_context.md      # This file
```

## 📦 Dependencies (CDN)

| Library | Version | Purpose |
|---------|---------|---------|
| Lightweight Charts | 4.2.1 | Candlestick & line charts |
| GPU.js | latest | GPU-accelerated calculations (WebGL backend) |
| WebGPU (Native) | Browser API | Native WGSL compute shaders (via `clsWGSLCompute.js`) |

## 🔧 Components Detail

### 1. DerivAPI (`deriv-api.js`)

**Purpose:** จัดการการเชื่อมต่อกับ Deriv WebSocket API

**Key Features:**
- WebSocket connection management
- Auto-reconnect (max 5 attempts)
- Historical candles fetching
- Live candles subscription
- Connection status callbacks

**API Endpoint:** `wss://ws.derivws.com/websockets/v3?app_id=1089`

**Methods:**
```javascript
connect()                                    // เชื่อมต่อ WebSocket
disconnect()                                 // ปิดการเชื่อมต่อ
getHistoricalCandles(symbol, granularity, count)  // ดึงข้อมูลย้อนหลัง
subscribeLiveCandles(symbol, granularity, callback) // Subscribe live data
unsubscribe()                               // ยกเลิก subscription
getActiveSymbols()                          // ดึงรายการ symbols
onConnectionChange(callback)                // Callback เมื่อสถานะเปลี่ยน
```

**Static Methods:**
```javascript
DerivAPI.formatCandles(candles)  // แปลง candles เป็น chart format
DerivAPI.formatOHLC(ohlc)        // แปลง live OHLC เป็น chart format
```

### 2. WebGPUIndicators (`webgpu-indicators.js`)

**Purpose:** คำนวณ Technical Indicators โดยใช้ GPU acceleration แบบ Hybrid

**Key Features:**
- **Hybrid Architecture**: Uses GPU for parallelizable tasks (SMA, RSI, TrueRange) and CPU for recursive tasks (EMA) for maximum efficiency and stability.
- **Super Kernel (Batch Processing)**: Can process multiple assets simultaneously using 2D kernels.
- **Robust Fallback**: Automatically switches to CPU if GPU initialization fails or runtime errors occur.
- **Dynamic Input**: Handles arrays of varying lengths (padding handled by loader).

**Indicators:**
| Indicator | Method | Mode | Description |
|-----------|--------|------|-------------|
| SMA | `calculateSMA` | GPU/CPU | Simple Moving Average |
| EMA | `calculateEMA` | **CPU** | Exponential Moving Average (Recursive, O(N)) |
| HMA | `calculateHMA` | GPU/CPU | Hull Moving Average |
| WMA | `calculateWMA` | CPU | Weighted Moving Average |
| RSI | `calculateRSI` | GPU/CPU | Relative Strength Index |
| Choppiness | `calculateChoppiness` | GPU/CPU | Choppiness Index |
| **Batch** | `calculateBatch` | **GPU (2D)** | Super Kernel for RSI & Choppiness on multiple assets |

**GPU Kernels (1D & 2D):**
- `sma`, `smaBatch`: Simple Moving Average
- `priceChanges`, `priceChangesBatch`: Pre-calc for RSI
- `rsi`, `rsiBatch`: RSI Calculation
- `trueRange`, `trueRangeBatch`: True Range for Choppiness

### 3. MultiAssetLoader (`multi-asset-loader.js`)

**Purpose:** Load and calculate data for multiple assets efficiently.

**Key Features:**
- **Parallel Loading**: Fetches data for multiple symbols concurrently.
- **Super Kernel Integration**: Normalizes data (padding) and sends 2D arrays to WebGPUIndicators for batch processing.
- **Performance Stats**: Measures load time and calculation time.

**Methods:**
```javascript
loadMultipleAssets(symbols, granularity, count) // Load data only
calculateAllIndicators(maType, periods, rsiPeriod, useSuperKernel) // Calculate only
loadAndCalculate(symbols, ..., useSuperKernel) // Load + Calculate (All-in-one)
```

### 4. ChartManager (`chart-manager.js`)

**Purpose:** จัดการ Lightweight Charts ทั้ง 3 charts

**Charts:**
1. **Main Chart** - Candlestick + MA lines
2. **RSI Chart** - RSI line + level lines (30, 70)
3. **Choppiness Chart** - Choppiness line + level lines (38.2, 61.8)

**Key Features:**
- Thai timezone conversion (UTC+7)
- Synchronized timescales between charts
- Auto-scaling price axis
- Responsive resize handling

**Methods:**
```javascript
updateCandles(candles)           // Update main chart candles
updateMA(index, data, visible)   // Update MA line (0, 1, or 2)
updateRSI(data)                  // Update RSI chart
updateChoppiness(data)           // Update Choppiness chart
updateLiveCandle(candle)         // Update single live candle
updateLiveMA(index, point)       // Update single MA point
updateLiveRSI(point)             // Update single RSI point
updateLiveChoppiness(point)      // Update single Choppiness point
resize()                         // Handle window resize
```

### 5. TradingApp (`app.js`)

**Purpose:** Main application controller

**Key Features:**
- Component initialization
- Event listeners setup
- Data flow management
- Throttled indicator updates
- Error handling & display

**Data Structure:**
```javascript
this.currentData = {
    candles: [],  // Array of {time, open, high, low, close}
    highs: [],    // Array of high prices
    lows: [],     // Array of low prices
    closes: []    // Array of close prices
}
```

### 6. MultiAssetManager (`MultiAssetBundle.js`)

**Purpose:**  
Consolidates `DerivAPI`, `WebGPUIndicators`, and `MultiAssetLoader` into a single, easy-to-use manager class. It simplifies initialization and execution of multi-asset analysis tasks.

**Key Features:**
- **Unified Interface:** Single entry point (`execute`) for fetching data and calculating indicators.
- **Flexible Parameters:** Supports fetching by "Latest Count" or "Date Range".
- **Analysis Hook:** Includes an placeholder `analysisData` method for post-processing results.

**Methods:**
```javascript
execute(params) 
// params: { assets, startDate, stopDate, latest, duration, durationUnit, useSuperKernel }
// Returns: { success, assets, indicators, stats, analysis }

analysisData(data)
// Hook for implementing custom analysis logic on the calculated indicators.
// Hook for implementing custom analysis logic on the calculated indicators.
```

### 7. AnalysisGeneratorV2 (`clsAnalysisGeneratorV2.js`)

**Purpose:**
Advanced analysis generator that produces a detailed array of analysis data for each candle, including trend, volatility, momentum, and Smart Money Concepts (SMC).

**New Features:**
- **GPU Acceleration**: Now supports passing a `WebGPUIndicators` instance to accelerate RSI and Choppiness Index calculations.
- **Detailed Output**: Generates a comprehensive `analysisList` with formatted fields for easy consumption by UI or trading logic.

**Usage with GPU:**
```javascript
const gpu = new WebGPUIndicators();
await gpu.initialize();
const generator = new AnalysisGeneratorV2(candleData, options, gpu);
const analysis = generator.generate(); // Uses GPU for RSI/Choppy
```

### 8. WGSLComputeEngine (`js/clsWGSLCompute.js`) ★ NEW

**Purpose:**
Native WebGPU (WGSL) compute engine สำหรับคำนวณ Technical Indicators (EMA, RSI, Choppiness Index)
เป็น standalone class ที่นำไปใช้ซ้ำได้ในทุก HTML page

**Key Features:**
- **Smart CPU/GPU Auto-Selection**: เลือก GPU หรือ CPU อัตโนมัติตามขนาด data
  - ≤ 1,000 data points → ใช้ CPU (เร็วกว่า เพราะไม่มี `mapAsync` overhead)
  - > 1,000 data points → ใช้ GPU WGSL (parallel compute ชนะ)
- **Singleton Pattern**: Device + pipeline ถูก cache ไว้ สร้างครั้งเดียว
- **GPU Batch Processing**: ALL assets → 1 encoder → 1 submit → 1 mapAsync
- **WGSL Compute Shaders**: 4 shaders (PriceChanges, RSI, TrueRange, Choppiness)
- **CPU Fallback**: ถ้า WebGPU ไม่รองรับ จะ fallback ไป CPU อัตโนมัติ
- **Timing Report**: ให้ข้อมูล performance breakdown (compute, ema, total)

**WGSL Shaders:**
| Shader | Workgroup Size | Purpose |
|--------|---------------|---------|  
| `WGSL_PRICE_CHANGES` | 64 | Calculate price changes (prices[i] - prices[i-1]) |
| `WGSL_RSI` | 64 | RSI from price changes |
| `WGSL_TRUE_RANGE` | 64 | True Range from H/L/C |
| `WGSL_CHOPPINESS` | 64 | Choppiness Index from H/L/TR |

**Methods:**
```javascript
// Singleton — สร้าง/ดึง cached engine
const engine = await WGSLComputeEngine.getInstance();

// High-level API — คำนวณทุก indicator อัตโนมัติ (แนะนำ)
const { results, timing } = await engine.compute(assetsData, {
    emaPeriods: { short: 9, medium: 25, long: 99 },
    rsiPeriod: 14,
    ciPeriod: 14,
    forceGPU: false,   // บังคับใช้ GPU
    forceCPU: false,   // บังคับใช้ CPU
    gpuThreshold: 1000 // threshold สำหรับเลือก GPU
});

// Individual indicators (CPU)
engine.computeEMA(prices, period)             // → number[]
engine.computeRSI(prices, period)             // → number[]
engine.computeCI(highs, lows, closes, period) // → number[]

// GPU batch (สำหรับ data มาก)
const gpuResults = await engine.computeGPU(assetsData, rsiPeriod, ciPeriod);

// Utility
engine.isGPUAvailable() // → boolean
engine.getInfo()        // → { gpuAvailable, gpuThreshold, pipelinesCompiled, cached }
```

**Input Format:**
```javascript
const assetsData = [
    { closes: [100.5, 101.2, ...], highs: [101.0, ...], lows: [99.0, ...] },
    { closes: [...], highs: [...], lows: [...] },  // asset 2
];
```

**Output Format:**
```javascript
{
    results: [
        {
            ema: { short: [...], medium: [...], long: [...] },
            rsi: [50, 50, ..., 65.3, 58.1, ...],
            ci:  [50, 50, ..., 42.1, 55.8, ...]
        },
    ],
    timing: {
        total: 5.23,       // ms
        compute: 3.45,     // ms (RSI + CI)
        ema: 1.66,         // ms
        method: 'GPU (WGSL)' // or 'CPU'
    }
}
```

## ⚙️ Configuration Options

### Symbols (Synthetic Indices)
| Value | Name |
|-------|------|
| R_10 | Volatility 10 Index |
| R_25 | Volatility 25 Index |
| R_50 | Volatility 50 Index |
| R_75 | Volatility 75 Index |
| R_100 | Volatility 100 Index |
| 1HZ10V | Volatility 10 (1s) Index |
| 1HZ25V | Volatility 25 (1s) Index |
| 1HZ50V | Volatility 50 (1s) Index |
| 1HZ75V | Volatility 75 (1s) Index |
| 1HZ100V | Volatility 100 (1s) Index |

### Timeframes
| Value (seconds) | Display |
|-----------------|---------|
| 60 | 1M (1 minute) |
| 180 | 3M (3 minutes) |
| 900 | 15M (15 minutes) |
| 1800 | 30M (30 minutes) |

### Indicator Settings
| Setting | Default | Range |
|---------|---------|-------|
| MA Period 1 | 9 | 1-200 |
| MA Period 2 | 21 | 1-200 |
| MA Period 3 | 50 | 1-200 |
| RSI Period | 14 | 2-100 |
| Choppiness Period | 14 | 2-100 |

## 🔄 Data Flow

```
1. User clicks "Load History"
   │
   ├─► DerivAPI.getHistoricalCandles()
   │   └─► WebSocket request to Deriv
   │
   ├─► DerivAPI.formatCandles()
   │   └─► Convert to chart format
   │
   ├─► ChartManager.updateCandles()
   │   └─► Convert to Thai time & display
   │
   └─► TradingApp.calculateAndUpdateIndicators()
       ├─► WebGPUIndicators.calculateEMA/SMA/HMA()
       ├─► WebGPUIndicators.calculateRSI()
       ├─► WebGPUIndicators.calculateChoppiness()
       └─► ChartManager.updateMA/RSI/Choppiness()

2. User clicks "Start Live"
   │
   ├─► DerivAPI.subscribeLiveCandles()
   │   └─► WebSocket subscription
   │
   └─► On each tick:
       ├─► DerivAPI.formatOHLC()
       ├─► ChartManager.updateLiveCandle()
       ├─► Update currentData array
       └─► Throttled indicator recalculation
```

## 🐛 Known Issues & Fixes Applied

### 1. GPU is not a constructor
**Problem:** GPU.js exports differently based on version
**Solution:** Check multiple export patterns (`GPU`, `GPU.GPU`, `window.GPU`)

### 2. req_id InputValidationFailed
**Problem:** Deriv API expects numeric `req_id`
**Solution:** Use `Date.now()` instead of string prefix

### 3. Live candle creating new bars every tick
**Problem:** Using `epoch` timestamp instead of `open_time`
**Solution:** Use `open_time` for candle time to properly update existing candle

### 4. Kernel resize error
**Problem:** GPU.js kernels can't resize without `dynamicOutput`
**Solution:** Add `.setDynamicOutput(true)` to all kernels + CPU fallback

### 5. Time not matching Thai timezone
**Problem:** Deriv API returns UTC timestamps
**Solution:** Add 7 hours (25200 seconds) to all timestamps in ChartManager

### 6. Chart scaling too large on scroll
**Problem:** Price axis auto-scaling on mouse drag
**Solution:** Set `handleScale.axisPressedMouseMove.price: false`

### 7. RSI/Choppiness charts not displaying
**Problem:** Level lines using invalid timestamps (0, 9999999999)
**Solution:** Use actual data time range for level lines

### 8. Indicator calculation incomplete / GPU TDR
**Problem:** Recursive calculations (EMA) on GPU caused timeouts/crashes.
**Solution:** 
- Switched EMA to **CPU** (O(N) recursive is faster on CPU).
- Implemented **Robust Fallback**: If GPU fails, switches to CPU instantly.
- Added **Super Kernel** (Batch) mode for true parallel processing of multiple assets.

## 🚀 Usage

1. เปิด `index.html` ใน browser
2. เลือก Symbol และ Timeframe
3. กด **Load History** เพื่อโหลดข้อมูลย้อนหลัง
4. กด **Start Live** เพื่อเริ่มรับข้อมูล real-time
5. ปรับ indicator settings แล้วกด **Update Indicators**
6. Toggle MA lines ด้วย checkboxes

**For Multi-Asset Demo:**
1. Open `multiAsset.html`.
2. Wait for initialization.
3. Check **"Use Super Kernel"** for batch processing.
4. Click **Parallel Load**.

**For Multi-Asset Bundle Test:**
1. Open `test_multi_asset_bundle.html`.
2. Configure Asset list, Data Mode (Latest/Range), and Duration.
3. Toggle **Enable Super Kernel**.
4. Click **Run Analysis** to see detailed results and logs.

**For Multi-Asset Bundle V2 (GPU + WGSL Analysis):**
1. Open `testMultiAssetBundleV2.html`.
2. **Select Assets**: The page automatically fetches valid Volatility Indices (e.g., R_10, 1HZ10V) from Deriv API. Select which assets to analyze from the dynamic list.
3. **Configure**: Set duration, count.
4. Click **GPU.js Analysis** or **WebGPU WGSL Analysis**.
   - **GPU.js**: Uses WebGL-based GPU.js library.
   - **WGSL**: Uses native WebGPU with smart CPU/GPU auto-selection via `clsWGSLCompute.js`.
5. View the results table with color-coded indicators and timing comparison.
6. The **Timing Panel** shows performance comparison between GPU.js and WGSL.

**For WebGPU (WGSL) Reference Example:**
1. Open `wgpl.html`.
2. This is a standalone benchmark page demonstrating native WebGPU compute.
3. Shows the pattern: single encoder → compute passes → copy → submit → single mapAsync.

## 📊 Indicator Interpretation

### RSI (Relative Strength Index)
- **> 70:** Overbought (อาจมีการ pullback)
- **< 30:** Oversold (อาจมีการ bounce)
- **50:** Neutral

### Choppiness Index
- **> 61.8:** Choppy/Sideways market (ไม่ควรเทรด trend)
- **< 38.2:** Trending market (เหมาะกับ trend following)
- **38.2-61.8:** Neutral zone

### Moving Averages
- **MA1 (Fast):** Short-term trend
- **MA2 (Medium):** Medium-term trend
- **MA3 (Slow):** Long-term trend
- **Golden Cross:** MA1 > MA2 > MA3 (Bullish)
- **Death Cross:** MA1 < MA2 < MA3 (Bearish)

## ⚡ Performance Notes — GPU.js vs WebGPU (WGSL)

### Benchmark Results (ทดสอบจริง 2026-02-11)

| Data Points | ผู้ชนะ | เหตุผล |
|---|---|---|
| ≤ 1,000 | **CPU** | `mapAsync` overhead (~5-10ms) > compute time |
| > 1,000 | **WebGPU (WGSL)** | Parallel compute 64 threads ชนะ overhead |
| Any | **GPU.js** ใกล้เคียง CPU | `gl.readPixels()` synchronous = low overhead |

### Key Insights
- **GPU.js (WebGL)**: `gl.readPixels()` → synchronous → ได้ผลทันที (0ms overhead)
- **WebGPU (WGSL)**: `mapAsync()` → async round-trip → ~5-10ms minimum latency per call
- **Solution**: Smart hybrid — ใช้ CPU เมื่อ data น้อย, GPU เมื่อ data มาก
- **EMA**: ทำบน CPU เสมอ (sequential: ema[i] = f(ema[i-1]))
- **GPU Batch**: ALL assets → 1 encoder → 1 submit → 1 mapAsync (ลด round-trips)

## 🔮 Future Improvements

- [ ] Add more indicators (MACD, Bollinger Bands, ATR)
- [x] ~~Multiple symbol comparison~~ (implemented in testMultiAssetBundleV2)
- [x] ~~Native WebGPU (WGSL) support~~ (implemented in clsWGSLCompute.js)
- [x] ~~Smart CPU/GPU auto-selection~~ (threshold: 1000 points)
- [x] ~~Drawing tools~~ (Price lines in indexV5.html)
- [ ] Save/Load settings to localStorage
- [ ] Alert notifications
- [ ] Export chart as image
- [ ] Dark/Light theme toggle
- [ ] Mobile responsive improvements
- [ ] Add more WGSL shaders (MACD, Bollinger Bands, ATR)
- [ ] Rust/Tauri desktop application migration

## 9. Deriv Accumulator Bot (`testDerivBot.html`)

**Purpose:**
Automated trading bot for Deriv **Accumulator** contracts. It executes trades based on user-defined parameters and monitors ticks in real-time to secure profits.

**Files:**
- `testDerivBot.html`: Main UI, authentication, configuration, and trade monitoring table.
- `testDerivBotTrade.html`: (Alternative logic/testing variant) Contains specific trade execution logic.
- `autoSaveInputs.js`: Helper script to automatically save/restore input fields to `localStorage`.

**Key Features:**
- **Accumulator Trading**: Supports Growth Rate, Stake, and Take Profit settings.
- **Real-time Monitoring**:
  - Displays **Spot Price**, **Profit**, **Tick Count**, **Barriers**, and **Gap**.
  - Tracks **Price Change** per tick with color coding.
- **Auto Take Profit**: Automatically sells when:
  - Profit reaches target USD.
  - Tick Count reaches target N (Sniper Mode).
- **Safety Mechanisms**:
  - **Stream Watchdog**: Detects stalled streams and forces status check.
  - **Retry Logic**: Retries sell requests on failure.
  - **Pre-fill Data**: Populates initial contract data to valid "gap" calculation from Tick 1.
- **Session Reporting**: Tracks Wins, Losses, and Total Profit for the session.

**Monitor Table Columns:**
| Column | Description |
|---|---|
| **Trade No** | Sequence number of the trade loop |
| **ID** | Contract ID |
| **Time** | Local time of the update |
| **Spot** | Current spot price |
| **Change** | Price difference from previous tick (+Green/-Red) |
| **Profit** | Current profit/loss in USD |
| **Tick Count** | Number of surviving ticks (Compound Interest ticks) |
| **High Bar** | Upper Barrier Price (Knock-out level) |
| **Low Bar** | Lower Barrier Price (Knock-out level) |
| **Gap** | Distance to the nearest barrier (Critical for survival) |

### New Features (v2.0):
- **Chart Barrier Shading**: 
  - Uses `BackgroundColorZonesPlugin.js` to visually shade the "safe zone" between high and low barriers.
  - Dynamically updates with trade progress.
- **Chart Modes**:
  - **Time (1m)**: Standard candlestick chart based on Epoch time.
  - **Every Tick**: Step-like candle chart where every tick creates a new bar (Tick Counter X-axis).
- **Trade Data Export**:
  - collects detailed tick-by-tick data for every trade.
  - Generates JSON output for analysis.
  - **Visual Markers**: Adds Buy/Sell markers on the chart (Entry, Win, Loss).