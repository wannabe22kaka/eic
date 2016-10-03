package com.WordFilter;

import java.io.IOException;
import java.sql.SQLException;
import java.util.HashMap;

import java.sql.ResultSet;
import java.sql.SQLException;

import com.DBControll.StandardDBController;


public class PositiveorNegativeWord {
	
	private HashMap<String, Integer> m_WordDirection;
	
	public PositiveorNegativeWord(){
		m_WordDirection = new HashMap<String, Integer>();

    }
	
	
	public void loadData() 
			throws IOException, InterruptedException, SQLException
	{
		
		StandardDBController controller = new StandardDBController("org.gjt.mm.mysql.Driver","jdbc:mysql://localhost:3306/","standard_data");
		controller.setUserName("hive");
		controller.setPassWord("740412");
		try {
			controller.connect();
		} catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		ResultSet result = controller.selectTable("KeywordDirection");
		while(result.next()){

			int i=1;
	       String keyword = result.getString(i++);
	       int direction = result.getInt(i++);
	       this.addData(keyword,direction);
		}
		
	}
	
	private void addData(String _keyword,Integer _direction){
		
		m_WordDirection.put(_keyword, _direction);
		
	}
	
    
	public int	findWord(String _word){
		if(m_WordDirection.get(_word) == null)
			return  0;
		else
			return m_WordDirection.get(_word);
		
	}
	
}