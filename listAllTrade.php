<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการเทรดที่เปิดค้างอยู่</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        
        .login-section {
            padding: 30px;
            text-align: center;
        }
        
        .login-form {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
        
        .status {
            padding: 15px;
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            font-weight: 600;
        }
        
        .status.loading {
            color: #3b82f6;
        }
        
        .status.connected {
            color: #10b981;
        }
        
        .status.error {
            color: #ef4444;
        }
        
        .content {
            padding: 20px;
            display: none;
        }
        
        .content.show {
            display: block;
        }
        
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }
        
        .refresh-info {
            color: #6b7280;
            font-size: 14px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }
        
        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }
        
        td {
            padding: 12px 8px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        tbody tr {
            transition: background-color 0.2s;
        }
        
        tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .contract-type {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .type-call {
            background: #d1fae5;
            color: #065f46;
        }
        
        .type-put {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .profit-positive {
            color: #10b981;
            font-weight: 600;
        }
        
        .profit-negative {
            color: #ef4444;
            font-weight: 600;
        }
        
        .time-remaining {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #3b82f6;
        }
        
        .summary {
            padding: 20px;
            background: #f9fafb;
            border-top: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }
        
        .action-btn {
            padding: 6px 12px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-sell {
            background: #ef4444;
            color: white;
        }
        
        .btn-sell:hover {
            background: #dc2626;
        }
        
        .btn-sell:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }
        
        .help-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>
	
	<script src="https://thepapers.in/phpAllPredictAPI/autoSaveInputs.js" ></script>
	
</head>
<body>
    <div class="container">
        <h1>🎯 รายการเทรดที่เปิดค้างอยู่</h1>
        
        <div id="loginSection" class="login-section">
            <div class="login-form">
                <div class="form-group">
                    <label for="appId">App ID:</label>
                    <input type="text" id="appId" value="1089" placeholder="ใส่ App ID">
                    <p class="help-text">ใช้ 1089 สำหรับทดสอบ</p>
                </div>
                <div class="form-group">
                    <label for="apiToken">API Token:</label>
                    <input type="password" id="apiToken" placeholder="ใส่ API Token ของคุณ" value='lt5UMO6bNvmZQaR'>
                    <p class="help-text">Token ต้องมี scope: read, trade</p>
                </div>
                <button class="btn btn-primary" onclick="connectAndFetch()">เชื่อมต่อและดึงข้อมูล</button>
            </div>
        </div>
        
        <div id="status" class="status" style="display: none;"></div>
        
        <div id="content" class="content">
            <div class="controls">
                <div class="refresh-info">
                    🔄 อัปเดตอัตโนมัติทุก 2 วินาที <input type="checkbox" id="useFetchCandle" checked>
                </div>
				<button class="btn btn-primary" onclick="SendPortfolio()">Refresh</button>
                <button class="btn btn-danger" onclick="disconnect()">ตัดการเชื่อมต่อ</button>
            </div>
			<div class="controls">
			 <table>
			 <tr>
				<td>Asset :</td>
				<td><input type="textbox" id="symbol" value=''> </td>
				<td>countBar : </td>
				<td><input type="number" id="countBar" value=20></td>
			 </tr>
			 <tr>
				<td>Use Check Target :</td>
				<td><input type="checkbox" id="useCheckTarget" checked></td>
				<td>Use Check Turn : </td>
				<td><input type="checkbox" id="useCheckTurn" checked></td>
			 </tr>
			 <tr>
				<td>Sale Condition :</td>
				<td><select id="SaleCondition" onchange='SetSaleCondition()'>
					<option value="TargetOnly" selected>ตรวจ Target อยางเดียว
					<option value="TargetAndTurn">ตรวจ Target+การเกิด การกลับตัว
				</select>
				<span id='spanSaleCondition' style='color:red;font-weight:bold'></span>
				</td>
				<td>Target Money : </td>
				<td><input type="number" onchange= 'SaveLocal()' id="targetMoney" value=10></td>
			 </tr>
			 <tr>
				<td>Min Profit :</td>
				<td><input type="number" id="minProfit" value=0></td>
				<td>Max Profit : </td>
				<td><input type="number" id="maxProfit" value=0></td>
			 </tr>

			 <tr>
				<td>Direction List :</td>
				<td id='directionList'></td>
				<td>Turn List ?? : </td>
				<td id='turnList'></td>
			 </tr>

			 <tr>
				<td>Current EMA Direction :</td>
				<td><input type="text" id="emaDirection" value=0></td>
				<td>Is Turn Occur ?? : </td>
				<td id=''>
				  <input type="checkbox" id="turntype999">
				</td>
			 </tr>

			 <tr>
				<td>ServerTime :</td>
				<td id='serverTime'></td>
				<td>Last 2 Direction ?? : </td>
				<td><input type="textbox" id="last2Direction" value=''></td>
			 </tr>


			 </table>
			 
                
				    
                   
                
                
            </div>

            
            <div id="emptyState" class="empty-state" style="display: none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3>ไม่มีรายการเทรดที่เปิดค้างอยู่</h3>
                <p>คุณไม่มี contract ที่เปิดอยู่ในขณะนี้</p>
            </div>
            
            <div id="tableWrapper" class="table-wrapper" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>Contract ID</th>
                            <th>Symbol</th>
                            <th>ประเภท</th>
                            <th>ราคาซื้อ</th>
                            <th>Payout</th>
                            <th>กำไร/ขาดทุน</th>
                            <th>เวลาซื้อ</th>
                            <th>เวลาหมดอายุ</th>
                            <th>เวลาที่เหลือ</th>
							<th>Min Profit</th>
							<th>Max Profit</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="contractsTable"></tbody>
                </table>
            </div>
        </div>
        
        <div id="summary" class="summary" style="display: none;"></div>
    </div>

    <script>
        let ws = null;
        let updateInterval = null;
		let SaleCondition = document.getElementById("SaleCondition").value ;
        let ContractList = [];
		let minProfit = document.getElementById("minProfit").value ;
		let maxProfit = document.getElementById("minProfit").value ;


		function SetSaleCondition() {
		
		     SaleCondition = document.getElementById("SaleCondition").value ;
			 document.getElementById("spanSaleCondition").innerHTML = SaleCondition;
			 
		
		} // end func
		


        function showStatus(message, type = 'loading') {
            const statusEl = document.getElementById('status');
            statusEl.textContent = message;
            statusEl.className = `status ${type}`;
            statusEl.style.display = 'block';
        }

        function hideStatus() {
            document.getElementById('status').style.display = 'none';
        }

        function connectAndFetch() {
            const appId = document.getElementById('appId').value.trim();
            const apiToken = document.getElementById('apiToken').value.trim();

            if (!appId || !apiToken) {
                alert('กรุณาใส่ App ID และ API Token');
                return;
            }

            document.getElementById('loginSection').style.display = 'none';
            showStatus('กำลังเชื่อมต่อ...', 'loading');

            ws = new WebSocket(`wss://ws.derivws.com/websockets/v3?app_id=${appId}`);

            ws.onopen = () => {
                showStatus('เชื่อมต่อสำเร็จ - กำลัง authorize...', 'connected');
                
                // ต้อง authorize ก่อน
                const authRequest = {
                    authorize: apiToken
                };
                ws.send(JSON.stringify(authRequest));
				subscribeToTime();
            };

            ws.onmessage = (event) => {
                const data = JSON.parse(event.data);
                //console.log('Received:', data); // Debug log
	 			if (data.time) {
                   updateServerTime(data.time);
                }

                if (data.error) {
                    showStatus(`ข้อผิดพลาด: ${data.error.message}`, 'error');
                    console.error('Error details:', data.error);
                    setTimeout(() => {
                        disconnect();
                    }, 3000);
                    return;
                }
				if (data.msg_type === 'candles') {
					//alert('Candles')
                    console.log(data.candles);
					emaArray= calculateEMA(data.candles,5);
					//console.log('EMA=',emaArray);
					turnList = analyzeEMASlope(emaArray, threshold = 0.01) ;
					//console.log(turnList);
					turnListSt = '';
					directionListSt = '';


					for (let i=0;i<=turnList.length-1 ;i++ ) {
					  turnListSt += turnList[i].turn + '-'; 
					  directionListSt += turnList[i].direction + '-'; 
					}
					//turnListSt = turnList.join(',');
					document.getElementById("directionList").innerHTML = directionListSt;
					document.getElementById("turnList").innerHTML = turnListSt;
                     
                     
                     
                    lastIndex = turnList.length -1 ;
					console.log('lastIndex',lastIndex);
					console.log('Turn',JSON.stringify(turnList));
                    console.log('lastTurn',turnList[lastIndex].turn);

					st = turnList[lastIndex-1].direction +'-' + turnList[lastIndex].direction ;
                    document.getElementById("last2Direction").value = st; 

             
					if (turnList[lastIndex].turn != '') {
						document.getElementById("turntype999").checked = true;
					} else {
                        document.getElementById("turntype999").checked = false;
					}

					if (st === 'Down-Up' || st === 'Up-Down') {
                       document.getElementById("turntype999").checked = true;
					}
					
					
					

                    

				}

                // รับ authorize response แล้วดึง portfolio
                if (data.msg_type === 'authorize') {
                    //console.log('Authorized successfully');
                    showStatus('Authorized - กำลังดึงข้อมูล...', 'connected');
                    const portfolioRequest = {
                        portfolio: 1
                    };
                    //console.log('Sending portfolio request:', portfolioRequest);
                    ws.send(JSON.stringify(portfolioRequest));
                }

                if (data.msg_type === 'portfolio') {
                    console.log('Portfolio received:', data.portfolio);
                    contractsData = data.portfolio.contracts || [];
					sendTrackOrderRequests(contractsData);
                    displayContracts(contractsData);
                    hideStatus();
                    document.getElementById('content').classList.add('show');
                    
                    // ดึงข้อมูลซ้ำทุก 2 วินาที
					/*
                    if (updateInterval) clearInterval(updateInterval);
                    updateInterval = setInterval(() => {
                        if (ws && ws.readyState === WebSocket.OPEN) {
                            ws.send(JSON.stringify({ portfolio: 1 }));
                        }
                        updateTimeRemaining();
                    }, 2000);
					*/
                }

				if (data.proposal_open_contract) {
                   //console.log(data)
                    
                   const contract = data.proposal_open_contract;                  
				   //console.log(contract.contract_id,'=',contract.profit)
				   tdID = 'profit_' + contract.contract_id ;
				   MinprofitID = 'Minprofit_' + contract.contract_id ; 
                   MaxprofitID = 'Maxprofit_' + contract.contract_id ; 
				   //console.log(MinprofitID,' - ',MaxprofitID);
				   //SetSaleCondition

				   if (contract.profit > 0) {
                      target = parseFloat(document.getElementById("targetMoney").value) ; 
                      turnTypeOccur = document.getElementById("turntype999").checked;    

                      //if (document.getElementById("useCheckTarget").checked && turnTypeOccur) {
					    console.log(SaleCondition)
						  
                       if (SaleCondition ==='TargetOnly') {
						   if (contract.profit > target) {						   
						     sellContract(contract.contract_id) ;
						   }
					   }
					   if (SaleCondition === 'TargetAndTurn') {
						   if (contract.profit > target && turnTypeOccur===true) {
						     sellContract(contract.contract_id) ;
						   }
					   }

                       
					  
					  stSpan = '<span style="font-size:18px;color:#008000">'+ contract.profit +  '</span>['+ contract.profit*32 + ']';
				   } else {
                      stSpan = '<span style="font-size:18px;color:red">'+ contract.profit + '</span>[' +contract.profit*32+']';
				   }
                   sTime = calculateTimeRemaining(contract.expiry_time);
				   expiryID = 'expiryTime_' +contract.contract_id ;
				   document.getElementById(expiryID).innerHTML = sTime;
				   
				   document.getElementById(tdID).innerHTML = stSpan ;
				   if (contract.profit > maxProfit) {
					   maxProfit = contract.profit ;
					   document.getElementById("maxProfit").value = maxProfit;
					   document.getElementById(MaxprofitID).innerHTML = maxProfit;
				   }
				   if (contract.profit < minProfit) {
					   minProfit = contract.profit ;
					   document.getElementById("minProfit").value = minProfit;
					   document.getElementById(MinprofitID).innerHTML = minProfit;
				   }
				}
              
				if (data.proposal_open_contract ) {
					const isSold = data?.proposal_open_contract?.is_sold;
					//const contract = data.proposal_open_contract; 
					if (isSold === 1) {					
						thisid = 'message_' + data.proposal_open_contract.contract_id ;
						thisMsg= 'สัญญาสิ้นสุดแล้ว  กำไร/ขาดทุน: '+ data.proposal_open_contract.profit;
						document.getElementById(thisid).innerHTML = thisMsg;
						playSoldSound();
					}					
				}
            };

            ws.onerror = (error) => {
                showStatus('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            };

            ws.onclose = () => {
                showStatus('การเชื่อมต่อถูกปิด', 'error');
                if (updateInterval) clearInterval(updateInterval);
            };
        }

        function disconnect() {
            if (ws) {
                ws.close();
                ws = null;
            }
            if (updateInterval) {
                clearInterval(updateInterval);
                updateInterval = null;
            }

			return;
            document.getElementById('loginSection').style.display = 'block';
            document.getElementById('content').classList.remove('show');
            document.getElementById('summary').style.display = 'none';
            hideStatus();
        }  

		function playSoldSound() {
         const audio = new Audio('electronic-door-bell-39969.mp3');
         audio.volume = 0.5; // ปรับระดับเสียง 0.0 - 1.0
         audio.play().catch(e => console.error('Cannot play sound:', e));

        }

		function SendPortfolio() {
			     
				 if (!ws) {
					 alert('Connection Close');
					 return ;
				 } 
                 connectAndFetch()

		         const portfolioRequest = {
                        portfolio: 1
                 };
                 //console.log('Sending portfolio request:', portfolioRequest);
                 ws.send(JSON.stringify(portfolioRequest));
				 /*
				 const request = {
                   proposal_open_contract: 1,
                   contract_id: contractId,
                   subscribe: 1 // ขอ subscribe ข้อมูลเพื่อติดตามการเปลี่ยนแปลง
                 };
				 
				 */
		
		} // end func
		

		function sendTrackOrderRequests(contractsData) {

             console.log('Send Track Order Requests',contractsData)
               
			for (let i=0;i<=contractsData.length-1 ;i++ ) {
				 console.log(contractsData[i].contract_id) ;
				 thisID = contractsData[i].contract_id ;
				 found = false;
				 for (let i2=0;i2<=ContractList.length-1 ;i2++ ) {
                    console.log(ContractList[i2] ,'vs',thisID)
                    
				    if (ContractList[i2] === thisID) {
						found = true ; break ;
				    }
				 }
                 if (found === false) {                 
					 ContractList.push(contractsData[i].contract_id);
					 ws.send(JSON.stringify( { 
					   proposal_open_contract: 1, 
					   contract_id: contractsData[i].contract_id, 
					   subscribe: 1 
               		 }));
                 }

			}

		
		
		} // end func
		

        function formatTimestamp(timestamp) {
            const date = new Date(timestamp * 1000);
            const thaiOffset = 7 * 60 * 60 * 1000;
            const thaiTime = new Date(date.getTime() + thaiOffset);
            
            const hours = String(thaiTime.getUTCHours()).padStart(2, '0');
            const minutes = String(thaiTime.getUTCMinutes()).padStart(2, '0');
            const seconds = String(thaiTime.getUTCSeconds()).padStart(2, '0');
            
            return `${hours}:${minutes}:${seconds}`;
        }

        function calculateTimeRemaining(expiryTime) {
            const now = Math.floor(Date.now() / 1000);
            const remaining = expiryTime - now;
            
            if (remaining <= 0) return '00:00:00';
            
            const hours = Math.floor(remaining / 3600);
            const minutes = Math.floor((remaining % 3600) / 60);
            const seconds = remaining % 60;
            
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        function displayContracts(contracts) {
            const emptyState = document.getElementById('emptyState');
            const tableWrapper = document.getElementById('tableWrapper');
            const summaryEl = document.getElementById('summary');

            if (contracts.length === 0) {
                emptyState.style.display = 'block';
                tableWrapper.style.display = 'none';
                summaryEl.style.display = 'none';
                return;
            } 
			

            emptyState.style.display = 'none';
            tableWrapper.style.display = 'block';
            summaryEl.style.display = 'flex';

            const tbody = document.getElementById('contractsTable');
            tbody.innerHTML = '';

            contracts.forEach((contract, index) => {
                 
                
                thisContractID = contract.contract_id; 
				//console.log(thisContractID);
				found = false;
				for (let i=0;i<=ContractList.length-1 ;i++ ) {
                   if (ContractList[i] === thisContractID ) {
					   found = true ; break ;
                   }
			
			    }
				 //console.log('Found',found)
				//if (found === false) {
                if (found === true) {
                    
					const tr = document.createElement('tr');
					tr.dataset.contractId = contract.contract_id;
					
					const profit = (contract.bid_price || 0) - contract.buy_price;
					const profitClass = profit >= 0 ? 'profit-positive' : 'profit-negative';
					const profitSign = profit >= 0 ? '+' : '';
					
					const contractTypeClass = contract.contract_type === 'CALL' ? 'type-call' : 'type-put';
					document.getElementById("symbol").value = contract.symbol;
					
					tr.innerHTML = `
						<td><strong>${index + 1}</strong></td>
						<td>${contract.contract_id}</td>
						<td><strong>${contract.symbol}</strong></td>
						<td><span class="contract-type ${contractTypeClass}">${contract.contract_type}</span></td>
						<td>$${contract.buy_price.toFixed(2)}</td>
						<td>$${contract.payout.toFixed(2)}</td>
						<td id="profit_${contract.contract_id}" class="${profitClass}">${profitSign}$${profit.toFixed(2)}</td>
						<td>${formatTimestamp(contract.purchase_time)}</td>
						<td>${formatTimestamp(contract.expiry_time)}</td>
						<td 
						id="expiryTime_${contract.contract_id}"
						class="time-remaining">${calculateTimeRemaining(contract.expiry_time)}</td>

						<td id="Minprofit_${contract.contract_id}" class="${profitClass}"></td>
						<td id="Maxprofit_${contract.contract_id}" style="color:green"></td>


						<td id="message_${contract.contract_id}">
							<button class="action-btn btn-sell" onclick="sellContract(${contract.contract_id})">
								ขาย
							</button>
						</td>
					`;
					
					tbody.appendChild(tr);
				}
            });

            updateSummary(contracts);
        }

        function updateTimeRemaining() {
            contractsData.forEach((contract, index) => {
                const row = document.querySelector(`tr[data-contract-id="${contract.contract_id}"]`);
                if (row) {
                    const timeCell = row.querySelector('.time-remaining');
                    if (timeCell) {
                        timeCell.textContent = calculateTimeRemaining(contract.expiry_time) 
                    }
					
					

                }
            });
        }

        function updateSummary(contracts) {
            const totalContracts = contracts.length;
            const totalInvested = contracts.reduce((sum, c) => sum + c.buy_price, 0);
            const totalPayout = contracts.reduce((sum, c) => sum + c.payout, 0);
            const totalProfit = contracts.reduce((sum, c) => sum + ((c.bid_price || 0) - c.buy_price), 0);

            const summaryEl = document.getElementById('summary');
            summaryEl.innerHTML = `
                <div class="summary-item">
                    <div class="summary-label">จำนวน Contract</div>
                    <div class="summary-value">${totalContracts}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">เงินลงทุนรวม</div>
                    <div class="summary-value">$${totalInvested.toFixed(2)}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Payout รวม</div>
                    <div class="summary-value">$${totalPayout.toFixed(2)}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">กำไร/ขาดทุนรวม</div>
                    <div class="summary-value" style="color: ${totalProfit >= 0 ? '#10b981' : '#ef4444'}">
                        ${totalProfit >= 0 ? '+' : ''}$${totalProfit.toFixed(2)}
                    </div>
                </div>
            `;
        } 
		// Calculate EMA
		function calculateEMA(data, period) {
		  const k = 2 / (period + 1);
		  const emaValues = [];

		  // First EMA is SMA
		  let sum = 0;
		  for (let i = 0; i < period && i < data.length; i++) {
			sum += data[i].close;
		  }
		  emaValues.push(sum / Math.min(period, data.length));

		  // Calculate remaining EMAs
		  for (let i = period; i < data.length; i++) {
			const ema = data[i].close * k + emaValues[emaValues.length - 1] * (1 - k);
			emaValues.push(ema);
		  }

		  return emaValues;
		}



        function sellContract(contractId) {
			/*
            if (!confirm(`คุณต้องการขาย Contract ID: ${contractId} หรือไม่?`)) {
                return;
            }
			*/

            const sellRequest = {
                sell: contractId,
                price: 0
            };

            ws.send(JSON.stringify(sellRequest));

			thisid = 'message_' + contractId ;
			document.getElementById(thisid).innerHTML = 'ส่งคำสั่งขาย Contract แล้ว';
			
            //alert('ส่งคำสั่งขาย Contract แล้ว');
        }

		function subscribeToTime() {
			/*
			   if (timeSubscription) {
				  clearInterval(timeSubscription);
			   }
            */
			   ws.send(JSON.stringify({
				  "time": 1
			   }));
			   timeSubscription = setInterval(() => {
				  if (ws && ws.readyState === WebSocket.OPEN) {
					 ws.send(JSON.stringify({
						"time": 1
					 }));
				  }
			   }, 1000);
			   
			}

			function fetchCandles(){
              // {"ticks_history":"RDBEAR","count":584,"end":"latest","style":"candles","granu//larity":60,"req_id":1}
			  asset = document.getElementById("symbol").value ;
			  countBar = document.getElementById("countBar").value ;
			  granularity = 60 ;

			  request = {
				"ticks_history" : asset,                    
				"count": countBar,           
                "end"  : 'latest', 
                "style" : 'candles',
                "granularity" : granularity,
				"req_id" : 1
			   };
			   ws.send(JSON.stringify(request));
			   console.log(request) ;
			   
			
			
			} // end func

			function analyzeEMASlope(emaArray, threshold = 0.01) {
			  const results = [];
			  
			  for (let i = 1; i < emaArray.length; i++) {
				const current = emaArray[i];
				const previous = emaArray[i - 1];
				const diff = current - previous;
				
				let direction;
				if (Math.abs(diff) < threshold) {
				  direction = 'Parallel';
				} else if (diff > 0) {
				  direction = 'Up';
				} else {
				  direction = 'Down';
				}
				
				// ตรวจสอบการเปลี่ยนทิศทาง
				let turn = '';
				if (i > 1) {
				  const prevDirection = results[i - 2].direction;
				  
				  if (prevDirection === 'Down' && direction === 'Up') {
					turn = 'TurnUp';
				  } else if (prevDirection === 'Up' && direction === 'Down') {
					turn = 'TurnDown';
				  }
				}
				
				results.push({
				  index: i,
				  value: current,
				  diff: diff.toFixed(6),
				  direction: direction,
				  turn: turn
				});
			  }
			  
			  return results;
			}

			

			function updateServerTime(timestamp) {

			   const date = new Date(timestamp * 1000);
			   const timeStr = date.toLocaleTimeString();
			   document.getElementById('serverTime').textContent = timeStr;
			   if (document.getElementById("useFetchCandle").checked) {			   
				   if (date.getSeconds() === 0 || date.getSeconds() === 30) {
					  fetchCandles();
				   }
			   }
			}

			function SaveLocal() {
			   sObj = {
                "contractid" : contractid ,
                "targetMoney" : targetMoney  
			   }
               //localStorage.setItem("pageLab"
			
			} // end func
			
    </script>
	<script>
	  
	  document.addEventListener('DOMContentLoaded', function() {
	      // โค้ดที่ต้องการให้ทำงานเมื่อ DOM โหลดเสร็จ
          
		  SetSaleCondition();
		  connectAndFetch();
	      console.log('DOM fully loaded and parsed');
	  });
	  
	</script>
	
	
</body>
</html>

