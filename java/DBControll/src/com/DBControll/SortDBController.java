package com.DBControll;

import java.sql.*;
import com.DBControll.DBController;


public class SortDBController extends DBController{
	
	public SortDBController(){

	}
	public SortDBController(String _driver, String _url, String _dbname){
		this.setDBDriver(_driver);
		this.setDBUrl(_url);
		this.setDBName(_dbname);
	}
	
		
	public void InsertJsonDB(String[] _value) throws SQLException, ClassNotFoundException{
		
		String strQuery = "INSERT INTO " +  this.getTablename() + " (keyword, sum, morpheme, keywordindex) VALUES(?,?,?,?)"; // ?로 대치

		PreparedStatement pstmt= con.prepareStatement(strQuery);

		pstmt.clearParameters();
		
		for(int i = 0; i < _value.length; i++)
		{
			pstmt.setString(i + 1,_value[i]);
			System.out.print(_value[i]);
			if(i == 1)
				pstmt.setInt(i + 1,  Integer.parseInt(_value[i]));
			
		}

		int rowCount = pstmt.executeUpdate();
		System.out.print(rowCount);

	}


	
	public ResultSet CheckBeTable(String _table){
		
		ResultSet result = null;
		String sqlCT = "select * from " + this.getDay() + _table;

		try {
			result= this.executeQuery(sqlCT);
			System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return result;
		
		
	}
	
	public void CreateSorttable(String _table){
		
		String sqlCT = "create table if not exists " + this.getDay() + _table + "(" +
				    "jsondata json"+
		        ");";
		try {
			int result = this.executeUpdate(sqlCT);
			System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}
	
	public void DeleteSorttable(String _table){
		
		String sqlCT = "delete from " + this.getDay() + _table;
		try {
			int result = this.executeUpdate(sqlCT);
			System.out.println(result);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}

}
